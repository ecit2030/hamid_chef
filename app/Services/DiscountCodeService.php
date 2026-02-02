<?php

namespace App\Services;

use App\Repositories\DiscountCodeRepository;
use App\Repositories\DiscountCodeUsageRepository;
use App\Exceptions\ValidationException;
use Carbon\Carbon;

class DiscountCodeService
{
    public function __construct(
        private DiscountCodeRepository $discountCodeRepo,
        private DiscountCodeUsageRepository $usageRepo
    ) {}

    /**
     * التحقق من صلاحية كود الخصم
     */
    public function validateCode(string $code, int $userId, float $amount): array
    {
        // 1. البحث عن الكود
        $discountCode = $this->discountCodeRepo->findByCode($code);

        if (!$discountCode) {
            throw new \Exception('الكود غير موجود');
        }

        // 2. التحقق من أن الكود نشط
        if (!$discountCode->is_active) {
            throw new \Exception('الكود غير نشط');
        }

        // 3. التحقق من تاريخ الصلاحية
        $now = Carbon::now();
        if ($now->lt($discountCode->start_date)) {
            throw new \Exception('الكود لم يبدأ بعد');
        }
        if ($now->gt($discountCode->end_date)) {
            throw new \Exception('الكود منتهي الصلاحية');
        }

        // 4. التحقق من عدد الاستخدامات الكلي
        if ($discountCode->usage_limit && $discountCode->usage_count >= $discountCode->usage_limit) {
            throw new \Exception('تم استنفاد عدد استخدامات الكود');
        }

        // 5. التحقق من استخدام المستخدم
        $userUsageCount = $this->discountCodeRepo->getUserUsageCount($discountCode->id, $userId);
        if ($userUsageCount >= $discountCode->per_user_limit) {
            throw new \Exception('لقد استخدمت هذا الكود من قبل');
        }

        // 6. التحقق من الحد الأدنى للطلب
        if ($amount < $discountCode->min_order_amount) {
            throw new \Exception(
                "الحد الأدنى للطلب هو {$discountCode->min_order_amount} ريال"
            );
        }

        // 7. حساب الخصم
        $discountAmount = $discountCode->calculateDiscount($amount);
        $finalAmount = $amount - $discountAmount;

        return [
            'valid' => true,
            'discount_code_id' => $discountCode->id,
            'code' => $discountCode->code,
            'type' => $discountCode->type,
            'value' => $discountCode->value,
            'original_amount' => $amount,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
        ];
    }

    /**
     * تطبيق الخصم وتسجيل الاستخدام
     */
    public function applyDiscount(int $codeId, int $userId, int $bookingId, array $amounts): void
    {
        // تسجيل الاستخدام
        $this->usageRepo->recordUsage([
            'discount_code_id' => $codeId,
            'user_id' => $userId,
            'booking_id' => $bookingId,
            'original_amount' => $amounts['original_amount'],
            'discount_amount' => $amounts['discount_amount'],
            'final_amount' => $amounts['final_amount'],
            'used_at' => Carbon::now(),
        ]);

        // زيادة عداد الاستخدام
        $discountCode = $this->discountCodeRepo->find($codeId);
        $discountCode->incrementUsage();
    }

    /**
     * الحصول على الأكواد المتاحة
     */
    public function getAvailableCodes()
    {
        return $this->discountCodeRepo->getAvailable();
    }

    /**
     * إحصائيات الكود
     */
    public function getCodeStatistics(int $id): array
    {
        return $this->discountCodeRepo->getUsageStats($id);
    }
}
