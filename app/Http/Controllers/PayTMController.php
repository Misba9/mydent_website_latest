<?php

namespace App\Http\Controllers;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Http\Requests\CreatePaytmDetailRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Transaction;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Class PayTMController
 */
class PayTMController extends AppBaseController
{
    // display a form for payment
    public function initiate(Request $request): View
    {
        $appointmentId = $request->appointmentId;
        $appointment = Appointment::whereId($appointmentId)->firstOrFail();

        if (! $this->canPayForAppointment($appointment)) {
            abort(403);
        }

        $doctor = Doctor::with('user')->whereId($appointment->doctor_id)->first();
        $patient = Patient::with('user')->whereId($appointment->patient_id)->first();

        return view('payments.paytm.index', compact('appointmentId', 'appointment', 'doctor', 'patient'));
    }

    /**
     * @return mixed
     */
    public function payment(CreatePaytmDetailRequest $request)
    {
        $input = $request->all();
        $appointmentId = $request->appointmentId;
        $appointment = Appointment::whereId($appointmentId)->firstOrFail();

        if (! $this->canPayForAppointment($appointment)) {
            abort(403);
        }

        $patient = Patient::with('user')->whereId($appointment->patient_id)->firstOrFail();
        $payment = PaytmWallet::with('receive');
        $loginUserId = getLogInUserId() ?: 0;

        $payment->prepare([
            'order' => $appointmentId.'|'.$loginUserId.'|'.time(),
            'user' => $patient['user']['id'],
            'mobile_number' => $input['mobile'],
            'email' => $input['email'],
            'amount' => $appointment->payable_amount,
            'callback_url' => route('paytm.callback'),
        ]);

        return $payment->receive();
    }

    /**
     * Obtain the payment information.
     *
     * @return object
     */
    public function paymentCallback()
    {
        $paytmTransaction = PaytmWallet::with('receive');
        $response = $paytmTransaction->response();
        $order_id = $paytmTransaction->getOrderId();
        [$appointmentId] = explode('|', $order_id);

        if ($paytmTransaction->isSuccessful()) {
            $appointment = Appointment::whereId($appointmentId)->first();
            if (! $appointment) {
                Flash::error(__('messages.flash.appointment_created_payment_not_complete'));

                return $this->redirectAfterPaytm();
            }

            $txnId = $response['TXNID'] ?? null;
            if ($txnId) {
                DB::transaction(function () use ($appointment, $response, $txnId) {
                    if (Transaction::where('transaction_id', $txnId)->exists()) {
                        return;
                    }

                    if ((int) $appointment->payment_type === Appointment::PAID) {
                        return;
                    }

                    $patient = Patient::with('user')->whereId($appointment->patient_id)->first();
                    if (! $patient || ! $patient->user) {
                        return;
                    }

                    Transaction::create([
                        'user_id' => $patient->user->id,
                        'transaction_id' => $txnId,
                        'appointment_id' => $appointment->appointment_unique_id,
                        'amount' => $appointment->payable_amount,
                        'type' => Appointment::PAYTM,
                        'meta' => json_encode($response),
                    ]);

                    $appointment->update([
                        'payment_method' => Appointment::PAYTM,
                        'payment_type' => Appointment::PAID,
                    ]);

                    Notification::create([
                        'title' => Notification::APPOINTMENT_PAYMENT_DONE_PATIENT_MSG,
                        'type' => Notification::PAYMENT_DONE,
                        'user_id' => $patient->user->id,
                    ]);
                });
            }

            Flash::success(__('messages.flash.appointment_created_payment_complete'));

            return $this->redirectAfterPaytm();
        }

        if ($paytmTransaction->isFailed()) {
            Flash::error(__('messages.flash.appointment_created_payment_not_complete'));

            return $this->redirectAfterPaytm();
        }

        if ($paytmTransaction->isOpen()) {
            Log::info('PayTM transaction still open', ['order_id' => $order_id]);
        }

        Flash::error(__('messages.flash.appointment_created_payment_not_complete'));

        return $this->redirectAfterPaytm();
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function failed(): RedirectResponse
    {
        Flash::error(__('messages.flash.appointment_created_payment_not_complete'));

        return $this->redirectAfterPaytm();
    }

    /**
     * Never Auth::loginUsingId from payment payload — redirect based on current session only.
     */
    private function redirectAfterPaytm(): RedirectResponse
    {
        if (! getLogInUser()) {
            return redirect(route('medicalAppointment'));
        }

        if (getLogInUser()->hasRole('patient')) {
            return redirect(route('patients.patient-appointments-index'));
        }

        if (getLogInUser()->hasRole('doctor')) {
            return redirect(route('doctors.appointments'));
        }

        return redirect(route('appointments.index'));
    }

    private function canPayForAppointment(Appointment $appointment): bool
    {
        $user = getLogInUser();
        if (! $user) {
            return (int) $appointment->payment_type !== Appointment::PAID;
        }

        if ($user->hasRole('clinic_admin')) {
            return true;
        }

        if ($user->hasRole('doctor') && $user->doctor) {
            return (int) $appointment->doctor_id === (int) $user->doctor->id;
        }

        if ($user->hasRole('patient') && $user->patient) {
            return (int) $appointment->patient_id === (int) $user->patient->id;
        }

        return false;
    }
}
