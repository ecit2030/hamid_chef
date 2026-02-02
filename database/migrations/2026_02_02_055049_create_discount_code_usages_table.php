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
        Schema::create('discount_code_usages', function (Blueprint $table) {
            $table->id();

            // العلاقات
            $table->unsignedBigInteger('discount_code_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('booking_id');

            // تفاصيل الاستخدام
            $table->decimal('original_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('final_amount', 10, 2);

            // الطوابع الزمنية
            $table->timestamp('used_at')->useCurrent();

            // المفاتيح الخارجية
            $table->foreign('discount_code_id')->references('id')->on('discount_codes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');

            // الفهارس
            $table->index('discount_code_id');
            $table->index('user_id');
            $table->index('booking_id');
            $table->index('used_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_code_usages');
    }
};
