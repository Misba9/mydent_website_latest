<?php

namespace Tests\Feature;

use Tests\TestCase;

class RazorpayWebhookTest extends TestCase
{
    public function test_webhook_rejects_missing_signature(): void
    {
        config(['payments.razorpay.webhook_secret' => 'whsec_test']);

        $this->postJson(route('razorpay.webhook'), [
            'event' => 'payment.captured',
        ])->assertStatus(401);
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        config(['payments.razorpay.webhook_secret' => 'whsec_test']);

        $this->postJson(
            route('razorpay.webhook'),
            ['event' => 'payment.captured'],
            ['X-Razorpay-Signature' => 'invalid']
        )->assertStatus(401);
    }

    public function test_webhook_accepts_valid_signature(): void
    {
        $secret = 'whsec_test';
        config(['payments.razorpay.webhook_secret' => $secret]);

        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_test_webhook',
                        'notes' => [],
                    ],
                ],
            ],
        ], JSON_THROW_ON_ERROR);

        $signature = hash_hmac('sha256', $payload, $secret);

        $this->call(
            'POST',
            route('razorpay.webhook'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Razorpay-Signature' => $signature,
            ],
            $payload
        )->assertOk();
    }
}
