<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CheckoutAmountFreezeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_verify_rejects_when_cart_changed_after_order_create_session(): void
    {
        $product = Product::create([
            'name' => 'Test Aligners',
            'description' => 'Test',
            'category' => 'Aligners',
            'price' => 1000,
            'discount_price' => 1000,
            'quantity' => 10,
        ]);

        $cart = [
            $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => 1,
                'price' => 1000,
                'image' => null,
            ],
        ];

        $pending = [
            'razorpay_order_id' => 'order_test_123',
            'amount_paise' => 105000, // 1000 + 5% tax
            'cart' => $cart,
            'name' => 'Test User',
            'phone' => '9999999999',
            'email' => 'buyer@example.com',
            'address' => 'Addr',
            'city' => 'City',
            'state' => 'State',
            'country' => 'India',
            'pincode' => '500001',
        ];

        // Tamper frozen amount so verify must fail closed.
        $pending['amount_paise'] = 1;

        $signature = hash_hmac(
            'sha256',
            'order_test_123|pay_test_123',
            config('payments.razorpay.secret') ?: 'test_secret'
        );

        config(['payments.razorpay.secret' => config('payments.razorpay.secret') ?: 'test_secret']);

        $response = $this->withSession([
            'cart' => $cart,
            'pending_order' => $pending,
        ])->postJson(route('checkout.razorpay.verify'), [
            'razorpay_payment_id' => 'pay_test_123',
            'razorpay_order_id' => 'order_test_123',
            'razorpay_signature' => hash_hmac(
                'sha256',
                'order_test_123|pay_test_123',
                config('payments.razorpay.secret')
            ),
        ]);

        $response->assertStatus(422);
        $this->assertStringContainsString('amount', strtolower($response->json('error') ?? ''));
    }
}
