<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Transaction;
use Exception;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class RazorpayController extends AppBaseController
{
    public function onBoard(Request $request): \Illuminate\Http\JsonResponse
    {
        $appointmentID = $request->appointmentId;
        $appointment = Appointment::whereId($appointmentID)->first();

        if (! $appointment) {
            return $this->sendError('Appointment not found.');
        }

        if (! $this->canInitiatePayment($appointment)) {
            return $this->sendError('Seems, you are not allowed to access this record.', 403);
        }

        if ((int) $appointment->payment_type === Appointment::PAID) {
            return $this->sendError('Appointment is already paid.');
        }

        $patient = Patient::with('user')->whereId($appointment->patient_id)->first();
        if (! $patient || ! $patient->user) {
            return $this->sendError('Patient not found.');
        }

        $amount = $appointment->payable_amount;
        $api = new Api(config('payments.razorpay.key'), config('payments.razorpay.secret'));
        $orderData = [
            'receipt' => (string) $appointment->appointment_unique_id,
            'amount' => (int) round(((float) $amount) * 100),
            'currency' => getCurrencyCode(),
            'notes' => [
                'email' => $patient->user->email,
                'name' => $patient->user->full_name,
                'appointmentID' => $appointmentID,
            ],
        ];
        $razorpayOrder = $api->order->create($orderData);
        $data['id'] = $razorpayOrder->id;
        $data['amount'] = $amount;
        $data['name'] = $patient->user->full_name;
        $data['email'] = $patient->user->email;
        $data['contact'] = $patient->user->contact;

        return $this->sendResponse($data, __('messages.flash.order_create'));
    }

    public function paymentSuccess(Request $request)
    {
        $input = $request->all();
        Log::info('RazorPay Payment Successfully', [
            'razorpay_payment_id' => $input['razorpay_payment_id'] ?? null,
            'razorpay_order_id' => $input['razorpay_order_id'] ?? null,
        ]);

        if (empty($input['razorpay_payment_id']) || empty($input['razorpay_signature'])) {
            Flash::error(__('messages.flash.appointment_created_payment_not_complete'));

            return redirect()->back();
        }

        try {
            $api = new Api(config('payments.razorpay.key'), config('payments.razorpay.secret'));
            $payment = $api->payment->fetch($input['razorpay_payment_id']);
            $orderId = $payment['order_id'] ?? ($input['razorpay_order_id'] ?? '');

            $generatedSignature = hash_hmac(
                'sha256',
                $orderId.'|'.$input['razorpay_payment_id'],
                config('payments.razorpay.secret')
            );

            if (! hash_equals($generatedSignature, (string) $input['razorpay_signature'])) {
                Flash::error(__('messages.flash.appointment_created_payment_not_complete'));

                return redirect()->back();
            }

            $appointmentID = $payment['notes']['appointmentID'] ?? null;
            if (! $appointmentID) {
                Flash::error(__('messages.flash.appointment_created_payment_not_complete'));

                return redirect()->back();
            }

            $this->markAppointmentPaidFromRazorpay($appointmentID, $payment->id, $payment->toArray());

            Flash::success(__('messages.flash.appointment_created_payment_complete'));

            if (! getLogInUser()) {
                return redirect(route('medicalAppointment'));
            }

            if (getLogInUser()->hasRole('patient')) {
                return redirect(route('patients.patient-appointments-index'));
            }

            return redirect(route('appointments.index'));
        } catch (Exception $e) {
            Log::error('Razorpay paymentSuccess failed: '.$e->getMessage());
            Flash::error(__('messages.flash.appointment_created_payment_not_complete'));

            return redirect()->back();
        }
    }

    public function paymentFailed(Request $request)
    {
        $data = $request->get('data');
        Log::info('payment failed', ['has_data' => ! empty($data)]);

        Flash::error(__('messages.flash.appointment_created_payment_not_complete'));

        if (! getLogInUser()) {
            return redirect(route('medicalAppointment'));
        }

        if (getLogInUser()->hasRole('patient')) {
            return redirect(route('patients.patient-appointments-index'));
        }

        return redirect(route('appointments.index'));
    }

    public function paymentSuccessWebHook(Request $request)
    {
        $webhookSecret = config('payments.razorpay.webhook_secret');
        $signature = $request->header('X-Razorpay-Signature');
        $payload = $request->getContent();

        if (empty($webhookSecret) || empty($signature)) {
            Log::warning('Razorpay webhook rejected: missing secret or signature');

            return response('Unauthorized', 401);
        }

        $expected = hash_hmac('sha256', $payload, $webhookSecret);
        if (! hash_equals($expected, $signature)) {
            Log::warning('Razorpay webhook signature mismatch');

            return response('Invalid signature', 401);
        }

        $input = $request->all();
        Log::info('webHook Razorpay', ['event' => $input['event'] ?? null]);

        if (($input['event'] ?? null) === 'payment.captured'
            && isset($input['payload']['payment']['entity'])) {
            $payment = $input['payload']['payment']['entity'];
            $appointmentID = $payment['notes']['appointmentID'] ?? null;
            $paymentId = $payment['id'] ?? null;

            if ($appointmentID && $paymentId) {
                $this->markAppointmentPaidFromRazorpay($appointmentID, $paymentId, $payment);
            }
        }

        return response('OK', 200);
    }

    private function markAppointmentPaidFromRazorpay($appointmentID, string $paymentId, array $paymentMeta): void
    {
        DB::transaction(function () use ($appointmentID, $paymentId, $paymentMeta) {
            if (Transaction::where('transaction_id', $paymentId)->exists()) {
                return;
            }

            $appointment = Appointment::whereId($appointmentID)->lockForUpdate()->first();
            if (! $appointment) {
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
                'transaction_id' => $paymentId,
                'appointment_id' => $appointment->appointment_unique_id,
                'amount' => intval($appointment->payable_amount),
                'type' => Appointment::RAZORPAY,
                'meta' => $paymentMeta,
            ]);

            $appointment->update([
                'payment_method' => Appointment::RAZORPAY,
                'payment_type' => Appointment::PAID,
            ]);

            Notification::create([
                'title' => Notification::APPOINTMENT_PAYMENT_DONE_PATIENT_MSG,
                'type' => Notification::PAYMENT_DONE,
                'user_id' => $patient->user->id,
            ]);
        });
    }

    private function canInitiatePayment(Appointment $appointment): bool
    {
        $user = getLogInUser();
        if (! $user) {
            // Guest front booking payments are allowed only for unpaid appointments.
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
