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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id')->from(100001);
            $table->uuid('uuid');
            $table->foreignId('parent_category_id')->nullable()->references('id')->on('categories');
            $table->foreignId('child_category_id')->nullable()->references('id')->on('categories');
            $table->string('name');
            $table->string('sku')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->json('tags')->nullable();
            $table->json('highlights')->nullable();
            $table->string('color')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->longText('meta_description')->nullable();
            $table->decimal('price_original', 13,2);
            $table->decimal('price_discounted', 13,2)->nullable();
            $table->decimal('tax_percentage', 13,2)->nullable();
            $table->string('availability');
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
        Schema::dropIfExists('products');
    }
};
