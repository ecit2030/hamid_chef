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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();

            // معلومات الكود الأساسية
            $table->string('code', 50)->unique();
            $table->text('description')->nullable();

            // نوع وقيمة الخصم
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2);

            // قيود الاستخدام
            $table->decimal('min_order_amount', 10, 2)->default(0);
            $table->decimal('max_discount_amount', 10, 2)->nullable();

            // تواريخ الصلاحية
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            // حدود الاستخدام
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('usage_count')->default(0);
            $table->unsignedInteger('per_user_limit')->default(1);

            // الحالة
            $table->boolean('is_active')->default(true);

            // تتبع
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // الطوابع الزمنية
            $table->timestamps();
            $table->softDeletes();

            // المفاتيح الخارجية
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('admins')->onDelete('set null');

            // الفهارس
            $table->index('code');
            $table->index('is_active');
            $table->index(['start_date', 'end_date']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
