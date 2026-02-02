<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountCodeUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'discount_code_id',
        'user_id',
        'booking_id',
        'original_amount',
        'discount_amount',
        'final_amount',
        'used_at',
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'used_at' => 'datetime',
    ];

    public $timestamps = false;

    /**
     * العلاقات | Relationships
     */
    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * الحصول على نسبة الخصم
     */
    public function getDiscountPercentageAttribute(): float
    {
        if ($this->original_amount == 0) {
            return 0;
        }

        return round(($this->discount_amount / $this->original_amount) * 100, 2);
    }

    /**
     * الحصول على المبلغ الموفر
     */
    public function getSavingsAttribute(): float
    {
        return $this->discount_amount;
    }
}
