# نظام أكواد الخصم - الكود المتبقي للتنفيذ

## ملاحظة مهمة

تم إكمال المرحلة 1 (Database) والمرحلة 2 (Models) بالكامل.
هذا الملف يحتوي على الكود الكامل للمراحل المتبقية.

---

## المرحلة 3: DTOs

### ملف: `app/DTOs/DiscountCodeDTO.php`

```php
<?php

namespace App\DTOs;

use App\Models\DiscountCode;

class DiscountCodeDTO extends BaseDTO
{
    public $id;
    public $code;
    public $description;
    public $type;
    public $value;
    public $min_order_amount;
    public $max_discount_amount;
    public $start_date;
    public $end_date;
    public $usage_limit;
    public $usage_count;
    public $per_user_limit;
    public $is_active;
    public $created_by;
    public $updated_by;
    public $created_at;
    public $updated_at;

    // Computed attributes
    public $usage_percentage;
    public $remaining_usages;
    public $status;
    public $is_expired;
    public $is_upcoming;

    public function __construct(
        $id,
        $code,
        $description,
        $type,
        $value,
        $min_order_amount,
        $max_discount_amount,
        $start_date,
        $end_date,
        $usage_limit,
        $usage_count,
        $per_user_limit,
        $is_active,
        $created_by = null,
        $updated_by = null,
        $created_at = null,
        $updated_at = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->description = $description;
        $this->type = $type;
        $this->value = $value;
        $this->min_order_amount = $min_order_amount;
        $this->max_discount_amount = $max_discount_amount;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->usage_limit = $usage_limit;
        $this->usage_count = $usage_count;
        $this->per_user_limit = $per_user_limit;
        $this->is_active = (bool) $is_active;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public static function fromModel(DiscountCode $discountCode): self
    {
        $dto = new self(
            $discountCode->id,
            $discountCode->code,
            $discountCode->description,
            $discountCode->type,
            $discountCode->value,
            $discountCode->min_order_amount,
            $discountCode->max_discount_amount,
            $discountCode->start_date?->toDateTimeString(),
            $discountCode->end_date?->toDateTimeString(),
            $discountCode->usage_limit,
            $discountCode->usage_count,
            $discountCode->per_user_limit,
            $discountCode->is_active,
            $discountCode->created_by,
            $discountCode->updated_by,
            $discountCode->created_at?->toDateTimeString(),
            $discountCode->updated_at?->toDateTimeString()
        );

        // Add computed attributes
        $dto->usage_percentage = $discountCode->usage_percentage;
        $dto->remaining_usages = $discountCode->remaining_usages;
        $dto->status = $discountCode->status;
        $dto->is_expired = $discountCode->is_expired;
        $dto->is_upcoming = $discountCode->is_upcoming;

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'min_order_amount' => $this->min_order_amount,
            'max_discount_amount' => $this->max_discount_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'usage_limit' => $this->usage_limit,
            'usage_count' => $this->usage_count,
            'per_user_limit' => $this->per_user_limit,
            'is_active' => $this->is_active,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'usage_percentage' => $this->usage_percentage,
            'remaining_usages' => $this->remaining_usages,
            'status' => $this->status,
            'is_expired' => $this->is_expired,
            'is_upcoming' => $this->is_upcoming,
        ];
    }
}
```

### ملف: `app/DTOs/DiscountCodeUsageDTO.php`

```php
<?php

namespace App\DTOs;

use App\Models\DiscountCodeUsage;

class DiscountCodeUsageDTO extends BaseDTO
{
    public $id;
    public $discount_code_id;
    public $user_id;
    public $booking_id;
    public $original_amount;
    public $discount_amount;
    public $final_amount;
    public $used_at;

    // Computed
    public $discount_percentage;
    public $savings;

    public function __construct(
        $id,
        $discount_code_id,
        $user_id,
        $booking_id,
        $original_amount,
        $discount_amount,
        $final_amount,
        $used_at
    ) {
        $this->id = $id;
        $this->discount_code_id = $discount_code_id;
        $this->user_id = $user_id;
        $this->booking_id = $booking_id;
        $this->original_amount = $original_amount;
        $this->discount_amount = $discount_amount;
        $this->final_amount = $final_amount;
        $this->used_at = $used_at;
    }

    public static function fromModel(DiscountCodeUsage $usage): self
    {
        $dto = new self(
            $usage->id,
            $usage->discount_code_id,
            $usage->user_id,
            $usage->booking_id,
            $usage->original_amount,
            $usage->discount_amount,
            $usage->final_amount,
            $usage->used_at?->toDateTimeString()
        );

        $dto->discount_percentage = $usage->discount_percentage;
        $dto->savings = $usage->savings;

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'discount_code_id' => $this->discount_code_id,
            'user_id' => $this->user_id,
            'booking_id' => $this->booking_id,
            'original_amount' => $this->original_amount,
            'discount_amount' => $this->discount_amount,
            'final_amount' => $this->final_amount,
            'used_at' => $this->used_at,
            'discount_percentage' => $this->discount_percentage,
            'savings' => $this->savings,
        ];
    }
}
```

### تحديث: `app/DTOs/BookingDTO.php`

أضف هذه الحقول إلى الـ properties:

```php
public $discount_code_id;
public $discount_amount;
public $original_amount;
```

أضف إلى الـ constructor parameters:

```php
$discount_code_id,
$discount_amount,
$original_amount,
```

أضف إلى الـ constructor body:

```php
$this->discount_code_id = $discount_code_id;
$this->discount_amount = $discount_amount;
$this->original_amount = $original_amount;
```

أضف إلى fromModel():

```php
$booking->discount_code_id ?? null,
$booking->discount_amount ?? 0,
$booking->original_amount ?? null,
```

أضف إلى toArray():

```php
'discount_code_id' => $this->discount_code_id,
'discount_amount' => $this->discount_amount,
'original_amount' => $this->original_amount,
```

---

## المرحلة 4: Repositories

### ملف: `app/Repositories/Contracts/DiscountCodeRepositoryInterface.php`

```php
<?php

namespace App\Repositories\Contracts;

interface DiscountCodeRepositoryInterface
{
    public function findByCode(string $code);
    public function getActive();
    public function getValid();
    public function getAvailable();
    public function getUserUsageCount(int $codeId, int $userId): int;
    public function getUsageStats(int $id): array;
}
```

### ملف: `app/Repositories/DiscountCodeRepository.php`

```php
<?php

namespace App\Repositories;

use App\Models\DiscountCode;
use App\Repositories\Contracts\DiscountCodeRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class DiscountCodeRepository extends BaseRepository implements DiscountCodeRepositoryInterface
{
    public function __construct(DiscountCode $model)
    {
        parent::__construct($model);
    }

    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function getActive()
    {
        return $this->model->active()->get();
    }

    public function getValid()
    {
        return $this->model->valid()->get();
    }

    public function getAvailable()
    {
        return $this->model->available()->get();
    }

    public function getUserUsageCount(int $codeId, int $userId): int
    {
        return $this->model->find($codeId)
            ->usages()
            ->where('user_id', $userId)
            ->count();
    }

    public function getUsageStats(int $id): array
    {
        $code = $this->find($id);

        return [
            'total_usages' => $code->usage_count,
            'total_discount_amount' => $code->usages()->sum('discount_amount'),
            'unique_users' => $code->usages()->distinct('user_id')->count('user_id'),
            'recent_usages' => $code->usages()
                ->with(['user', 'booking'])
                ->latest('used_at')
                ->limit(10)
                ->get(),
        ];
    }
}
```

### ملف: `app/Repositories/Contracts/DiscountCodeUsageRepositoryInterface.php`

```php
<?php

namespace App\Repositories\Contracts;

interface DiscountCodeUsageRepositoryInterface
{
    public function recordUsage(array $data);
    public function getUserUsages(int $userId);
    public function getCodeUsages(int $codeId);
}
```

### ملف: `app/Repositories/DiscountCodeUsageRepository.php`

```php
<?php

namespace App\Repositories;

use App\Models\DiscountCodeUsage;
use App\Repositories\Contracts/DiscountCodeUsageRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class DiscountCodeUsageRepository extends BaseRepository implements DiscountCodeUsageRepositoryInterface
{
    public function __construct(DiscountCodeUsage $model)
    {
        parent::__construct($model);
    }

    public function recordUsage(array $data)
    {
        return $this->create($data);
    }

    public function getUserUsages(int $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with(['discountCode', 'booking'])
            ->latest('used_at')
            ->get();
    }

    public function getCodeUsages(int $codeId)
    {
        return $this->model
            ->where('discount_code_id', $codeId)
            ->with(['user', 'booking'])
            ->latest('used_at')
            ->get();
    }
}
```

---

## تسجيل Repositories في ServiceProvider

### ملف: `app/Providers/RepositoryServiceProvider.php`

أضف هذه السطور إلى دالة register():

```php
$this->app->bind(
    \App\Repositories\Contracts\DiscountCodeRepositoryInterface::class,
    \App\Repositories\DiscountCodeRepository::class
);

$this->app->bind(
    \App\Repositories\Contracts\DiscountCodeUsageRepositoryInterface::class,
    \App\Repositories\DiscountCodeUsageRepository::class
);
```

---

**ملاحظة**: هذا جزء من الكود. سأكمل في ملف منفصل للمراحل المتبقية (Services, Controllers, إلخ).

**الخطوة التالية**:

1. انسخ الكود أعلاه وأنشئ الملفات
2. أخبرني عندما تنتهي لأكمل المراحل المتبقية

أو يمكنني إنشاء ملف آخر يحتوي على بقية الكود؟
