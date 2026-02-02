<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DiscountCode extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'start_date',
        'end_date',
        'usage_limit',
        'usage_count',
        'per_user_limit',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'per_user_limit' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقات | Relationships
     */
    public function usages(): HasMany
    {
        return $this->hasMany(DiscountCodeUsage::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    public function scopeAvailable($query)
    {
        return $query->active()
                    ->valid()
                    ->where(function ($q) {
                        $q->whereNull('usage_limit')
                          ->orWhereRaw('usage_count < usage_limit');
                    });
    }

    /**
     * التحقق من صلاحية الكود
     */
    public function isValid(): bool
    {
        $now = Carbon::now();

        // التحقق من أن الكود نشط
        if (!$this->is_active) {
            return false;
        }

        // التحقق من تاريخ الصلاحية
        if ($now->lt($this->start_date) || $now->gt($this->end_date)) {
            return false;
        }

        // التحقق من عدد الاستخدامات
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * التحقق من إمكانية استخدام الكود من قبل مستخدم معين
     */
    public function canBeUsedBy(int $userId): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        // التحقق من عدد استخدامات المستخدم
        $userUsageCount = $this->usages()
            ->where('user_id', $userId)
            ->count();

        if ($userUsageCount >= $this->per_user_limit) {
            return false;
        }

        return true;
    }

    /**
     * حساب قيمة الخصم
     */
    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'percentage') {
            $discount = ($amount * $this->value) / 100;

            // تطبيق الحد الأقصى للخصم إذا كان موجوداً
            if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                $discount = $this->max_discount_amount;
            }

            return round($discount, 2);
        }

        // خصم مبلغ ثابت
        return min($this->value, $amount);
    }

    /**
     * زيادة عداد الاستخدام
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * التحقق من الحد الأدنى للطلب
     */
    public function meetsMinimumOrder(float $amount): bool
    {
        return $amount >= $this->min_order_amount;
    }

    /**
     * الحصول على نسبة الاستخدام
     */
    public function getUsagePercentageAttribute(): ?float
    {
        if (!$this->usage_limit) {
            return null;
        }

        return round(($this->usage_count / $this->usage_limit) * 100, 2);
    }

    /**
     * الحصول على عدد الاستخدامات المتبقية
     */
    public function getRemainingUsagesAttribute(): ?int
    {
        if (!$this->usage_limit) {
            return null;
        }

        return max(0, $this->usage_limit - $this->usage_count);
    }

    /**
     * التحقق من انتهاء الصلاحية
     */
    public function getIsExpiredAttribute(): bool
    {
        return Carbon::now()->gt($this->end_date);
    }

    /**
     * التحقق من أن الكود سيبدأ قريباً
     */
    public function getIsUpcomingAttribute(): bool
    {
        return Carbon::now()->lt($this->start_date);
    }

    /**
     * الحصول على حالة الكود
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        if ($this->is_upcoming) {
            return 'upcoming';
        }

        if ($this->is_expired) {
            return 'expired';
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return 'exhausted';
        }

        return 'active';
    }
}
