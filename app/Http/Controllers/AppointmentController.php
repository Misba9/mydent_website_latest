<?php

namespace App\Http\Controllers;

use App\Events\DeleteAppointmentFromGoogleCalendar;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\CreateFrontAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserGoogleAppointment;
use App\Repositories\AppointmentRepository;
use App\Repositories\GoogleCalendarRepository;
use \PDF;
use Carbon\Carbon;
use Exception;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AppointmentController extends AppBaseController
{
    /** @var AppointmentRepository */
    private $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepo)
    {
        $this->appointmentRepository = $appointmentRepo;
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): \Illuminate\View\View
    {
        $allPaymentStatus = getAllPaymentStatus();
        $paymentStatus = Arr::except($allPaymentStatus, [Appointment::MANUALLY]);
        $paymentGateway = getPaymentGateway();

        return view('appointments.index', compact('allPaymentStatus', 'paymentGateway', 'paymentStatus'));
    }

    /**
     * Show the form for creating a new Appointment.
     *
     * @return Application|Factory|View
     */
    public function create(): \Illuminate\View\View
    {
        $data = $this->appointmentRepository->getData();

        return view('appointments.create', compact('data'));
    }

    /**
     * @throws ApiErrorException
     */
    public function store(CreateAppointmentRequest $request): JsonResponse
    {
        $input = $request->all();

        // Patients cannot book on behalf of another patient or forge charges.
        if (getLogInUser() && getLogInUser()->hasRole('patient')) {
            if (! getLogInUser()->patient) {
                return $this->sendError('Patient profile not found.', 403);
            }
            $input['patient_id'] = getLogInUser()->patient->id;
            if (! empty($input['service_id'])) {
                $service = Service::find($input['service_id']);
                if ($service) {
                    $input['payable_amount'] = $service->charges;
                }
            }
        }

        $appointment = $this->appointmentRepository->store($input);

        if ($input['payment_type'] == Appointment::STRIPE) {
            $result = $this->appointmentRepository->createSession($appointment);

            return $this->sendResponse([
                'appointmentId' => $appointment->id,
                'payment_type' => $input['payment_type'],
                $result,
            ], 'Stripe '.__('messages.appointment.session_created_successfully'));
        }

        if ($input['payment_type'] == Appointment::PAYSTACK) {
            if ($request->isXmlHttpRequest()) {
                return $this->sendResponse([
                    'redirect_url' => route('paystack.init', ['appointmentData' => $appointment]),
                    'payment_type' => $input['payment_type'],
                    'appointmentId' => $appointment->id,
                ], 'Paystack '.__('messages.appointment.session_created_successfully'));
            }

            return redirect(route('paystack.init'));
        }

        if ($input['payment_type'] == Appointment::PAYPAL) {
            if ($request->isXmlHttpRequest()) {
                return $this->sendResponse([
                    'redirect_url' => route('paypal.index', ['appointmentData' => $appointment]),
                    'payment_type' => $input['payment_type'],
                    'appointmentId' => $appointment->id,
                ], 'Paypal '.__('messages.appointment.session_created_successfully'));
            }

            return redirect(route('paypal.init'));
        }

        if ($input['payment_type'] == Appointment::RAZORPAY) {
            return $this->sendResponse([
                'payment_type' => $input['payment_type'],
                'appointmentId' => $appointment->id,
            ], 'Razorpay '.__('messages.appointment.session_created_successfully'));
        }

        if ($input['payment_type'] == Appointment::AUTHORIZE) {
            return $this->sendResponse([
                'payment_type' => $input['payment_type'],
                'appointmentId' => $appointment->id,
            ], 'Authorize '.__('messages.appointment.session_created_successfully'));
        }

        if ($input['payment_type'] == Appointment::PAYTM) {
            return $this->sendResponse([
                'payment_type' => $input['payment_type'],
                'appointmentId' => $appointment->id,
            ], 'Paytm '.__('messages.appointment.session_created_successfully'));
        }

        $url = route('appointments.index');

        if (getLogInUser()->hasRole('patient')) {
            $url = route('patients.patient-appointments-index');
        }
        $data = [
            'url' => $url,
            'payment_type' => $input['payment_type'],
            'appointmentId' => $appointment->id,
        ];

        return $this->sendResponse($data, __('messages.flash.appointment_create'));
    }

    /**
     * Display the specified Appointment.
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function show(Appointment $appointment)
    {
        if (getLogInUser()->hasRole('doctor')) {
            $doctor = Appointment::whereId($appointment->id)->whereDoctorId(getLogInUser()->doctor->id);
            if (! $doctor->exists()) {
                return redirect()->back();
            }
        } elseif (getLogInUser()->hasRole('patient')) {
            $patient = Appointment::whereId($appointment->id)->wherePatientId(getLogInUser()->patient->id);
            if (! $patient->exists()) {
                return redirect()->back();
            }
        }

        $appointment = $this->appointmentRepository->showAppointment($appointment);

        if (empty($appointment)) {
            Flash::error(__('messages.flash.appointment_not_found'));

            if (getLogInUser()->hasRole('patient')) {
                return redirect(route('patients.patient-appointments-index'));
            } else {
                return redirect(route('admin.appointments.index'));
            }
        }

        if (getLogInUser()->hasRole('patient')) {
            return view('patient_appointments.show')->with('appointment', $appointment);
        } else {
            return view('appointments.show')->with('appointment', $appointment);
        }
    }

    /**
     * Remove the specified Appointment from storage.
     */
    public function destroy(Appointment $appointment): JsonResponse
    {
        if (getLogInUser()->hasrole('patient')) {
            if ($appointment->patient_id !== getLogInUser()->patient->id) {
                return $this->sendError('Seems, you are not allowed to access this record.');
            }
        }
        $appointmentUniqueId = $appointment->appointment_unique_id;

        $transaction = Transaction::whereAppointmentId($appointmentUniqueId)->first();

        if ($transaction) {
            $transaction->delete();
        }

        $appointment->delete();

        return $this->sendSuccess(__('messages.flash.appointment_delete'));
    }

    /**
     * @return Application|Factory|View
     *
     * @throws Exception
     */
    public function doctorAppointment(Request $request): \Illuminate\View\View
    {
        $appointmentStatus = Appointment::ALL_STATUS;
        $paymentStatus = getAllPaymentStatus();

        return view('doctor_appointment.index', compact('appointmentStatus', 'paymentStatus'));
    }

    /**
     * @return Application|Factory|View|JsonResponse
     */
    public function doctorAppointmentCalendar(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $data = $this->appointmentRepository->getAppointmentsData();

            return $this->sendResponse($data, __('messages.flash.doctor_appointment'));
        }

        return view('doctor_appointment.calendar');
    }

    /**
     * @return Application|Factory|View
     */
    public function patientAppointmentCalendar(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $data = $this->appointmentRepository->getPatientAppointmentsCalendar();

            return $this->sendResponse($data, __('messages.flash.patient_appointment'));
        }

        return view('appointments.patient-calendar');
    }

    /**
     * @return Application|Factory|View|JsonResponse
     */
    public function appointmentCalendar(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $data = $this->appointmentRepository->getCalendar();

            return $this->sendResponse($data, __('messages.flash.appointment_retrieve'));
        }

        return view('appointments.calendar');
    }

    /**
     * @return Application|Factory|View
     */
    public function appointmentDetail(Appointment $appointment): \Illuminate\View\View
    {
        $appointment = $this->appointmentRepository->showDoctorAppointment($appointment);

        return view('doctor_appointment.show', compact('appointment'));
    }

    /**
     * @return mixed
     */
    public function changeStatus(Request $request)
    {
        $input = $request->all();

        if (getLogInUser()->hasRole('doctor')) {
            $doctor = Appointment::whereId($input['appointmentId'])->whereDoctorId(getLogInUser()->doctor->id);
            if (! $doctor->exists()) {
                return $this->sendError('Seems, you are not allowed to access this record.');
            }
        }

        $appointment = Appointment::findOrFail($input['appointmentId']);

        $appointment->update([
            'status' => $input['appointmentStatus'],
        ]);
        $fullTime = $appointment->from_time.''.$appointment->from_time_type.' - '.$appointment->to_time.''.$appointment->to_time_type.' '.' '.Carbon::parse($appointment->date)->format('jS M, Y');
        $patient = Patient::whereId($appointment->patient_id)->with('user')->first();
        $doctor = Doctor::whereId($appointment->doctor_id)->with('user')->first();
        if ($input['appointmentStatus'] == Appointment::CHECK_OUT) {
            Notification::create([
                'title' => Notification::APPOINTMENT_CHECKOUT_PATIENT_MSG.' '.getLogInUser()->full_name,
                'type' => Notification::CHECKOUT,
                'user_id' => $patient->user_id,
            ]);
            Notification::create([
                'title' => $patient->user->full_name.'\'s appointment check out by '.getLogInUser()->full_name.' at '.$fullTime,
                'type' => Notification::CHECKOUT,
                'user_id' => $doctor->user_id,
            ]);
        } elseif ($input['appointmentStatus'] == Appointment::CANCELLED) {
            $events = UserGoogleAppointment::with(['user'])->where('appointment_id', $appointment->id)->get();

            /** @var GoogleCalendarRepository $repo */
            $repo = App::make(GoogleCalendarRepository::class);

            $repo->destroy($events);

            Notification::create([
                'title' => Notification::APPOINTMENT_CANCEL_PATIENT_MSG.' '.getLogInUser()->full_name,
                'type' => Notification::CANCELED,
                'user_id' => $patient->user_id,
            ]);
            Notification::create([
                'title' => $patient->user->full_name.'\'s appointment cancelled by'.getLogInUser()->full_name.' at '.$fullTime,
                'type' => Notification::CANCELED,
                'user_id' => $doctor->user_id,
            ]);
        }

        return $this->sendSuccess(__('messages.flash.status_update'));
    }

    /**
     * @return mixed
     */
    public function cancelStatus(Request $request)
    {
        $appointment = Appointment::findOrFail($request['appointmentId']);
        if ($appointment->patient_id !== getLogInUser()->patient->id) {
            return $this->sendError('Seems, you are not allowed to access this record.');
        }
        $appointment->update([
            'status' => Appointment::CANCELLED,
        ]);

        $events = UserGoogleAppointment::with('user')
            ->where('appointment_id', $appointment->id)
            ->get()
            ->groupBy('user_id');

        foreach ($events as $userID => $event) {
            $user = $event[0]->user;
            DeleteAppointmentFromGoogleCalendar::dispatch($event, $user);
        }

        $fullTime = $appointment->from_time.''.$appointment->from_time_type.' - '.$appointment->to_time.''.$appointment->to_time_type.' '.' '.Carbon::parse($appointment->date)->format('jS M, Y');
        $patient = Patient::whereId($appointment->patient_id)->with('user')->first();

        $doctor = Doctor::whereId($appointment->doctor_id)->with('user')->first();
        Notification::create([
            'title' => $patient->user->full_name.' '.Notification::APPOINTMENT_CANCEL_DOCTOR_MSG.' '.$fullTime,
            'type' => Notification::CANCELED,
            'user_id' => $doctor->user_id,
        ]);

        return $this->sendSuccess(__('messages.flash.appointment_cancel'));
    }

    /**
     * @throws ApiErrorException
     */
    public function frontAppointmentBook(CreateFrontAppointmentRequest $request): JsonResponse
    {
        app()->setLocale(checkLanguageSession());
        $input = $request->all();
        // Front booking is free — no payment gateway flow
        $input['payable_amount'] = 0;
        $input['payment_type'] = Appointment::MANUALLY;
        $appointment = $this->appointmentRepository->frontSideStore($input);

        return $this->sendResponse([
            'payment_type' => Appointment::MANUALLY,
            'appointmentId' => $appointment->id,
            'free' => true,
        ], __('messages.flash.appointment_booked'));
    }

    public function frontHomeAppointmentBook(Request $request): RedirectResponse
    {
        $data = $request->all();

        return redirect()->route('medicalAppointment')->with(['data' => $data]);
    }

    /**
     * @return HigherOrderBuilderProxy|mixed|string
     *
     * @throws Exception
     */
    public function getPatientName(Request $request)
    {
        $checkRecord = User::whereEmail($request->email)->whereType(User::PATIENT)->first();

        if ($checkRecord != '') {
            return $this->sendResponse($checkRecord->full_name, 'Patient name retrieved successfully.');
        }

        return false;
    }

    /**
     * @return Application|RedirectResponse|Redirector
     *
     * @throws ApiErrorException
     */
    public function paymentSuccess(Request $request): RedirectResponse
    {
        $sessionId = $request->get('session_id');
        if (empty($sessionId)) {
            throw new UnprocessableEntityHttpException('session id is required');
        }
        setStripeApiKey();

        $sessionData = \Stripe\Checkout\Session::retrieve($sessionId);

        if (($sessionData->payment_status ?? null) !== 'paid') {
            Flash::error(__('messages.flash.appointment_created_payment_not_complete'));

            return redirect(route('medicalAppointment'));
        }

        $appointment = Appointment::whereAppointmentUniqueId($sessionData->client_reference_id)->first();
        if (! $appointment) {
            Flash::error(__('messages.flash.appointment_not_found'));

            return redirect(route('medical'));
        }

        $patientId = User::whereEmail($sessionData->customer_details->email)->pluck('id')->first();

        if (! Transaction::where('transaction_id', $sessionData->id)->exists()
            && (int) $appointment->payment_type !== Appointment::PAID) {
            Transaction::create([
                'user_id' => $patientId,
                'transaction_id' => $sessionData->id,
                'appointment_id' => $sessionData->client_reference_id,
                'amount' => intval($sessionData->amount_total / 100),
                'type' => Appointment::STRIPE,
                'meta' => $sessionData,
            ]);

            $appointment->update([
                'payment_method' => Appointment::STRIPE,
                'payment_type' => Appointment::PAID,
            ]);

            $patient = Patient::whereUserId($patientId)->with('user')->first();
            if ($patient) {
                Notification::create([
                    'title' => Notification::APPOINTMENT_PAYMENT_DONE_PATIENT_MSG,
                    'type' => Notification::PAYMENT_DONE,
                    'user_id' => $patient->user_id,
                ]);
            }
        }

        Flash::success(__('messages.flash.appointment_created_payment_complete'));

        if (parse_url(url()->previous(), PHP_URL_PATH) == '/medical-appointment') {
            return redirect(route('medicalAppointment'));
        }

        if (! getLogInUser()) {
            return redirect(route('medical'));
        }

        if (getLogInUser()->hasRole('patient')) {
            return redirect(route('patients.patient-appointments-index'));
        }

        return redirect(route('appointments.index'));
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function handleFailedPayment(): RedirectResponse
    {
        setStripeApiKey();

        Flash::error(__('messages.flash.appointment_created_payment_not_complete'));

        if (! getLogInUser()) {
            return redirect(route('medicalAppointment'));
        }

        if (getLogInUser()->hasRole('patient')) {
            return redirect(route('patients.patient-appointments-index'));
        }

        return redirect(route('appointments.index'));
    }

    /**
     * @return mixed
     *
     * @throws ApiErrorException
     */
    public function appointmentPayment(Request $request)
    {
        $appointmentId = $request['appointmentId'];
        $appointment = Appointment::whereId($appointmentId)->first();

        $result = $this->appointmentRepository->createSession($appointment);

        return $this->sendResponse($result, 'Session created successfully.');
    }

    /**
     * @return mixed
     */
    public function changePaymentStatus(Request $request)
    {
        $input = $request->all();
        if (getLogInUser()->hasRole('doctor')) {
            $doctor = Appointment::whereId($input['appointmentId'])->whereDoctorId(getLogInUser()->doctor->id);
            if (! $doctor->exists()) {
                return $this->sendError('Seems, you are not allowed to access this record.');
            }
        }

        $appointment = Appointment::with('patient')->findOrFail($input['appointmentId']);
        $transactionExist = Transaction::whereAppointmentId($appointment['appointment_unique_id'])->first();

        $appointment->update([
            'payment_type' => $input['paymentStatus'],
            'payment_method' => $input['paymentMethod'],
        ]);

        if (empty($transactionExist)) {
            $transaction = [
                'user_id' => $appointment->patient->user_id,
                'transaction_id' => Str::random(10),
                'appointment_id' => $appointment->appointment_unique_id,
                'amount' => $appointment->payable_amount,
                'type' => Appointment::MANUALLY,
                'status' => Transaction::SUCCESS,
                'accepted_by' => $input['loginUserId'],
            ];

            Transaction::create($transaction);
        } else {
            $transactionExist->update([
                'status' => Transaction::SUCCESS,
                'accepted_by' => $input['loginUserId'],
            ]);
        }

        $appointmentNotification = Transaction::with('acceptedPaymentUser')->whereAppointmentId($appointment['appointment_unique_id'])->first();

        $fullTime = $appointment->from_time.''.$appointment->from_time_type.' - '.$appointment->to_time.''.$appointment->to_time_type.' '.' '.Carbon::parse($appointment->date)->format('jS M, Y');
        $patient = Patient::whereId($appointment->patient_id)->with('user')->first();
        Notification::create([
            'title' => $appointmentNotification->acceptedPaymentUser->full_name.' changed the payment status '.Appointment::PAYMENT_TYPE[Appointment::PENDING].' to '.Appointment::PAYMENT_TYPE[$appointment->payment_type].' for appointment '.$fullTime,
            'type' => Notification::PAYMENT_DONE,
            'user_id' => $patient->user_id,
        ]);

        return $this->sendSuccess(__('messages.flash.payment_status_updated'));
    }

    public function cancelAppointment(Request $request, $patient_id, $appointment_unique_id): RedirectResponse
    {
        // Prefer signed temporary URLs. Keep Crypt decrypt fallback for older emails.
        $uniqueId = $appointment_unique_id;
        try {
            if (! $request->hasValidSignature()) {
                $uniqueId = Crypt::decryptString($appointment_unique_id);
            }
        } catch (\Throwable $e) {
            Flash::error(__('messages.flash.appointment_not_found'));

            return redirect(route('medical'));
        }

        $appointment = Appointment::whereAppointmentUniqueId($uniqueId)->wherePatientId($patient_id)->first();
        if (! $appointment) {
            Flash::error(__('messages.flash.appointment_not_found'));

            return redirect(route('medical'));
        }

        if ((int) $appointment->status !== Appointment::CANCELLED) {
            $appointment->update(['status' => Appointment::CANCELLED]);
        }

        Flash::success(__('messages.flash.appointment_cancel'));

        return redirect(route('medical'));
    }

    public function doctorBookAppointment(Doctor $doctor): RedirectResponse
    {
        $data = [];
        $data['doctor_id'] = $doctor['id'];

        return redirect()->route('medicalAppointment')->with(['data' => $data]);
    }

    public function serviceBookAppointment(Service $service): RedirectResponse
    {
        $data = [];
        $data['service'] = Service::whereStatus(Service::ACTIVE)->get()->pluck('name', 'id');
        $data['service_id'] = $service['id'];

        return redirect()->route('medicalAppointment')->with(['data' => $data]);
    }

    /**
     * @return bool|JsonResponse
     */
    public function createGoogleEventForDoctor(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->sendSuccess(__('messages.flash.operation_performed_success'));
        }

        return true;
    }

    /**
     * @return bool|JsonResponse
     */
    public function createGoogleEventForPatient(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->sendSuccess(__('messages.flash.operation_performed_success'));
        }

        return true;
    }

    public function manuallyPayment(Request $request): RedirectResponse
    {
        $input = $request->all();
        $appointment = Appointment::findOrFail($input['appointmentId'])->load('patient');

        if (getLogInUser()->hasRole('doctor')) {
            if (! getLogInUser()->doctor || (int) $appointment->doctor_id !== (int) getLogInUser()->doctor->id) {
                Flash::error(__('messages.flash.not_allow_access_record'));

                return redirect()->back();
            }
        } elseif (! getLogInUser()->hasRole('clinic_admin')) {
            Flash::error(__('messages.flash.not_allow_access_record'));

            return redirect()->back();
        }

        $existingPending = Transaction::whereAppointmentId($appointment->appointment_unique_id)
            ->where('type', Appointment::MANUALLY)
            ->where('status', Transaction::PENDING)
            ->exists();

        if ($existingPending) {
            Flash::success(__('messages.flash.appointment_created_payment_not_complete'));

            return redirect()->back();
        }

        $transaction = [
            'user_id' => $appointment->patient->user_id,
            'transaction_id' => Str::random(10),
            'appointment_id' => $appointment->appointment_unique_id,
            'amount' => $appointment->payable_amount,
            'type' => Appointment::MANUALLY,
            'status' => Transaction::PENDING,
        ];

        Transaction::create($transaction);

        if (parse_url(url()->previous(), PHP_URL_PATH) == '/medical-appointment') {
            return redirect(route('medicalAppointment'));
        }

        if (! getLogInUser()) {
            return redirect(route('medical'));
        }

        if (getLogInUser()->hasRole('patient')) {
            return redirect(route('patients.patient-appointments-index'));
        }

        if (getLogInUser()->hasRole('doctor')) {

            return redirect(route('doctors.appointments'));
        }

        return redirect(route('appointments.index'));
    }

    public function appointmentPdf($id)
    {
        $datas = Appointment::with(['patient.user', 'doctor.user', 'services'])->findOrFail($id);
        $this->authorize('view', $datas);
        $pdf = Pdf::loadView('appointment_pdf.invoice', ['datas' => $datas]);

        return $pdf->download('invoice.pdf');
    }
}
