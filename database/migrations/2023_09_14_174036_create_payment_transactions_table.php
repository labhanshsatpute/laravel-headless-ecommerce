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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->bigIncrements('id')->from(100001);
            $table->uuid('uuid');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('order_id')->references('id')->on('orders');
            $table->string('gateway');
            $table->string('status')->default('PENDING');
            $table->string('transaction_id')->nullable();
            $table->json('meta_data')->nullable();
            $table->decimal('amount',13,2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
