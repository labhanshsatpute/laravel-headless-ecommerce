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
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id')->from(100001);
            $table->string('name');
            $table->string('code')->unique();
            $table->text('summary')->nullable();
            $table->date('start_date');
            $table->date('expire_date');
            $table->string('discount_type');
            $table->double('discount_value', 13, 2);
            $table->double('minimum_purchase', 13, 2);
            $table->double('maximum_discount', 13, 2);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
