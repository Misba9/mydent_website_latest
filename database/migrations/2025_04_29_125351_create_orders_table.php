<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('phone');
        $table->string('email');
        $table->text('address');
        $table->string('country');
        $table->string('state');
        $table->string('city');
        $table->string('pincode');
        $table->string('payment_method');
        $table->text('products'); // JSON of ordered items
        $table->decimal('subtotal', 10, 2);
        $table->decimal('tax', 10, 2);
        $table->decimal('total', 10, 2);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
