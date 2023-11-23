<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->bigIncrements('id')->from(100001);
            $table->uuid('uuid');
            $table->foreignId('admin_id')->references('id')->on('admins');
            $table->morphs('referable');
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->text('summary')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE `admin_notifications` CHANGE `referable_type` `referable_type` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL');
        DB::statement('ALTER TABLE `admin_notifications` CHANGE `referable_id` `referable_id` BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
