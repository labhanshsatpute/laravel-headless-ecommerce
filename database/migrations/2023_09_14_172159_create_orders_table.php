<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id')->from(100001);
            $table->uuid('uuid');
            $table->string('ref_no')->unique();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone_primary')->nullable();
            $table->string('phone_alternate')->nullable();
            $table->string('home')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->longText('note')->nullable();
            $table->string('status');
            $table->string('payment_type');
            $table->string('payment_status')->nullable();
            $table->json('meta_data')->nullable();
            $table->decimal('gross_amount', 13, 2)->nullable();
            $table->decimal('tax_charges', 13, 2)->nullable();
            $table->decimal('coupon_discount', 13, 2)->nullable();
            $table->decimal('delivery_charges', 13, 2)->nullable();
            $table->decimal('cod_charges', 13, 2)->nullable();
            $table->decimal('total_amount', 13, 2);
            $table->timestamps();
            $table->softDeletes();
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
