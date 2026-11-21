<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Transaction;
use Flash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PayPalHttp\HttpException;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    public function onBoard(Request $request): JsonResponse
    {
        $appointment = Appointment::whereId($request->appointmentId)->first();

        $provider = new PayPalClient;

        $provider->getAccessToken();

        $data = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $appointment->id,
                    'amount' => [
                        'value' => $appointment->payable_amount,
                        'currency_code' => getCurrencyCode(),
                    ],
                ],
            ],
            'application_context' => [
                'cancel_url' => route('paypal.failed'),
                'return_url' => route('paypal.success'),
            ],
        ];

        $order = $provider->createOrder($data);

        return response()->json(['link' => $order['links'][1]['href'], 'status' => 200]);
    }

    public function failed(): RedirectResponse
    {
        Flash::error(__('messages.flash.appointment_created_payment_not_complete'));

        if (! getLogInUser()) {
            return redirect(route('medicalAppointment'));
        }

        if (getLogInUser()->hasRole('patient')) {
            return redirect(route('patients.patient-appointments-index'));
        }

        return redirect(route('appointments.index'));
    }

    public function success(Request $request): RedirectResponse
    {
        $clientId = config('payments.paypal.client_id');
        $clientSecret = config('payments.paypal.client_secret');
        $mode = config('payments.paypal.mode');

        $provider = new PayPalClient;      // To use express checkout.

        $provider->getAccessToken();

        $token = $request->get('token');

        $orderInfo = $provider->showOrderDetails($token);

        try {
            // Call API with your client and get a response for your call
            $response = $provider->capturePaymentOrder($token);
            // If call returns body in response, you can get the deserialized version from the result attribute of the response

            $appointmentID = $response['purchase_units'][0]['reference_id'];
            $captureId = $response['purchase_units'][0]['payments']['captures'][0]['id'];
            $appointment = Appointment::whereId($appointmentID)->first();
            if (! $appointment) {
                Flash::error(__('messages.flash.appointment_not_found'));

                return redirect(route('medical'));
            }

            $patient = Patient::with('user')->whereId($appointment->patient_id)->first();

            if (! Transaction::where('transaction_id', $captureId)->exists()
                && (int) $appointment->payment_type !== Appointment::PAID) {
                Transaction::create([
                    'user_id' => $patient->user->id,
                    'transaction_id' => $captureId,
                    'appointment_id' => $appointment['appointment_unique_id'],
                    'amount' => intval($appointment['payable_amount']),
                    'type' => Appointment::PAYPAL,
                    'meta' => json_encode($response),
                ]);

                $appointment->update([
                    'payment_method' => Appointment::PAYPAL,
                    'payment_type' => Appointment::PAID,
                ]);

                Notification::create([
                    'title' => Notification::APPOINTMENT_PAYMENT_DONE_PATIENT_MSG,
                    'type' => Notification::PAYMENT_DONE,
                    'user_id' => $patient->user->id,
                ]);
            }

            Flash::success(__('messages.flash.appointment_created_payment_complete'));

            if (! getLogInUser()) {
                return redirect(route('medicalAppointment'));
            }

            if (getLogInUser()->hasRole('patient')) {
                return redirect(route('patients.patient-appointments-index'));
            }

            return redirect(route('appointments.index'));
        } catch (HttpException $ex) {
            \Illuminate\Support\Facades\Log::error('PayPal capture failed', [
                'status' => $ex->statusCode,
                'message' => $ex->getMessage(),
            ]);
            Flash::error(__('messages.flash.appointment_created_payment_not_complete'));

            return redirect(route('medicalAppointment'));
        }
    }
}
