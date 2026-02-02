# دليل التنفيذ النهائي - نظام أكواد الخصم

## 🎯 الملخص التنفيذي

تم إكمال **15%** من النظام (Database + Models).
هذا الملف يحتوي على **دليل كامل** لإكمال الـ **85%** المتبقية.

---

## ✅ ما تم إنجازه

### المرحلة 1 & 2: Database + Models ✅

- ✅ 3 Migrations (تم التشغيل)
- ✅ 3 Models (مكتملة)
- ✅ جميع العلاقات والدوال

---

## 📝 خطة التنفيذ المتبقية

### نهج موصى به: **Backend أولاً**

```
المرحلة 3-7: Backend Core (يمكن اختباره عبر API)
├── DTOs
├── Repositories
├── Service
├── Controllers
├── Requests
└── Routes

المرحلة 8-11: UI & Polish
├── Resources
├── Policy
├── Frontend (Admin Panel)
└── Sidebar & Translations

المرحلة 12-13: Data & Permissions
├── Seeder & Factory
└── Permissions
```

---

## 🚀 التنفيذ السريع

### الخطوة 1: نسخ الملفات الجاهزة

الملفات التالية **جاهزة للنسخ** من `DISCOUNT_CODES_REMAINING_CODE.md`:

1. ✅ `app/DTOs/DiscountCodeDTO.php`
2. ✅ `app/DTOs/DiscountCodeUsageDTO.php`
3. ✅ تحديث `app/DTOs/BookingDTO.php`
4. ✅ `app/Repositories/Contracts/DiscountCodeRepositoryInterface.php`
5. ✅ `app/Repositories/DiscountCodeRepository.php`
6. ✅ `app/Repositories/Contracts/DiscountCodeUsageRepositoryInterface.php`
7. ✅ `app/Repositories/DiscountCodeUsageRepository.php`
8. ✅ تحديث `app/Providers/RepositoryServiceProvider.php`

### الخطوة 2: إنشاء Service (الأهم)

**ملف**: `app/Services/DiscountCodeService.php`

```php
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
            throw new ValidationException('الكود غير موجود');
        }

        // 2. التحقق من أن الكود نشط
        if (!$discountCode->is_active) {
            throw new ValidationException('الكود غير نشط');
        }

        // 3. التحقق من تاريخ الصلاحية
        $now = Carbon::now();
        if ($now->lt($discountCode->start_date)) {
            throw new ValidationException('الكود لم يبدأ بعد');
        }
        if ($now->gt($discountCode->end_date)) {
            throw new ValidationException('الكود منتهي الصلاحية');
        }

        // 4. التحقق من عدد الاستخدامات الكلي
        if ($discountCode->usage_limit && $discountCode->usage_count >= $discountCode->usage_limit) {
            throw new ValidationException('تم استنفاد عدد استخدامات الكود');
        }

        // 5. التحقق من استخدام المستخدم
        $userUsageCount = $this->discountCodeRepo->getUserUsageCount($discountCode->id, $userId);
        if ($userUsageCount >= $discountCode->per_user_limit) {
            throw new ValidationException('لقد استخدمت هذا الكود من قبل');
        }

        // 6. التحقق من الحد الأدنى للطلب
        if ($amount < $discountCode->min_order_amount) {
            throw new ValidationException(
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
```

### الخطوة 3: تحديث BookingService

أضف إلى `app/Services/BookingService.php`:

```php
use App\Services\DiscountCodeService;

public function __construct(
    // ... existing dependencies
    private DiscountCodeService $discountCodeService
) {}

// في دالة createWithConflictCheck، بعد إنشاء الحجز:
if ($bookingDTO->discount_code_id) {
    $this->discountCodeService->applyDiscount(
        $bookingDTO->discount_code_id,
        $bookingDTO->customer_id,
        $booking->id,
        [
            'original_amount' => $bookingDTO->original_amount,
            'discount_amount' => $bookingDTO->discount_amount,
            'final_amount' => $bookingDTO->total_amount,
        ]
    );
}
```

### الخطوة 4: API Controller

**ملف**: `app/Http/Controllers/Api/DiscountCodeController.php`

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DiscountCodeService;
use App\Http\Traits\SuccessResponse;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    use SuccessResponse;

    public function __construct(
        private DiscountCodeService $discountCodeService
    ) {}

    /**
     * التحقق من كود الخصم
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $result = $this->discountCodeService->validateCode(
                $request->code,
                $request->user()->id,
                $request->amount
            );

            return $this->resourceResponse($result, 'الكود صالح');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
```

### الخطوة 5: Routes

أضف إلى `routes/api.php`:

```php
// Discount Codes (للعملاء)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/discount-codes/validate', [
        App\Http\Controllers\Api\DiscountCodeController::class,
        'validate'
    ]);
});
```

---

## 🎨 Admin Panel (اختياري - يمكن تأجيله)

### الخطوة 6: Admin Controller

**ملف**: `app/Http/Controllers/Admin/DiscountCodeController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DiscountCodeService;
use App\Repositories\DiscountCodeRepository;
use Inertia\Inertia;

class DiscountCodeController extends Controller
{
    public function __construct(
        private DiscountCodeRepository $repo,
        private DiscountCodeService $service
    ) {}

    public function index()
    {
        $codes = $this->repo->paginate(15);

        return Inertia::render('Admin/DiscountCode/Index', [
            'codes' => $codes,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/DiscountCode/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:discount_codes|max:50',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            // ... المزيد
        ]);

        $validated['created_by'] = auth()->id();
        $this->repo->create($validated);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', 'تم إنشاء الكود بنجاح');
    }

    // ... المزيد من الدوال
}
```

### الخطوة 7: Admin Routes

أضف إلى `routes/admin.php`:

```php
Route::resource('discount-codes', App\Http\Controllers\Admin\DiscountCodeController::class);
```

---

## 📊 الخلاصة والخطوات التالية

### ما تم حتى الآن:

✅ Database (3 migrations)
✅ Models (3 models)

### ما يجب فعله الآن (بالترتيب):

#### **المرحلة الأولى - Backend الأساسي** (2-3 ساعات):

1. ✅ نسخ DTOs من `DISCOUNT_CODES_REMAINING_CODE.md`
2. ✅ نسخ Repositories من `DISCOUNT_CODES_REMAINING_CODE.md`
3. ✅ إنشاء `DiscountCodeService` (الكود أعلاه)
4. ✅ تحديث `BookingService` (الكود أعلاه)
5. ✅ إنشاء API Controller (الكود أعلاه)
6. ✅ إضافة API Routes (الكود أعلاه)

**بعد هذه الخطوات**: يمكنك اختبار النظام عبر Postman!

#### **المرحلة الثانية - Admin Panel** (3-4 ساعات):

1. إنشاء Admin Controller
2. إنشاء صفحات Vue (Index, Create, Edit, Show)
3. إضافة إلى Sidebar
4. إضافة الترجمات

#### **المرحلة الثالثة - التحسينات** (1-2 ساعة):

1. Seeder للبيانات التجريبية
2. Permissions
3. Tests

---

## 🧪 الاختبار

### اختبار API عبر Postman:

```http
POST http://localhost/api/discount-codes/validate
Authorization: Bearer {your_token}
Content-Type: application/json

{
  "code": "SUMMER2026",
  "amount": 500
}
```

**Response المتوقع**:

```json
{
    "success": true,
    "data": {
        "valid": true,
        "code": "SUMMER2026",
        "type": "percentage",
        "value": 20,
        "original_amount": 500,
        "discount_amount": 100,
        "final_amount": 400
    },
    "message": "الكود صالح"
}
```

---

## 💡 نصائح مهمة

1. **ابدأ بالـ Backend**: يمكنك اختباره فوراً عبر API
2. **استخدم Postman**: لاختبار الـ endpoints قبل بناء Frontend
3. **أنشئ بيانات تجريبية**: أضف كود خصم يدوياً في قاعدة البيانات للاختبار
4. **Frontend يمكن تأجيله**: النظام يعمل بدون واجهة إدارية

---

## 📁 الملفات المرجعية

1. **DISCOUNT_CODES_SYSTEM_PLAN.md** - الخطة الكاملة
2. **DISCOUNT_CODES_REMAINING_CODE.md** - DTOs & Repositories جاهزة
3. **هذا الملف** - دليل التنفيذ النهائي

---

## ✅ Checklist سريع

```
Backend Core:
□ نسخ DTOs
□ نسخ Repositories
□ تسجيل Repositories في ServiceProvider
□ إنشاء DiscountCodeService
□ تحديث BookingService
□ إنشاء API Controller
□ إضافة API Routes
□ اختبار عبر Postman

Admin Panel (اختياري):
□ Admin Controller
□ Vue Pages
□ Sidebar Link
□ Translations

Polish:
□ Seeder
□ Permissions
□ Tests
```

---

**الوقت المتوقع للإكمال**:

- Backend فقط: 2-3 ساعات
- Backend + Frontend: 5-7 ساعات
- كامل مع Tests: 8-10 ساعات

**هل تريد أن أبدأ بإنشاء الملفات المتبقية تلقائياً؟** 🚀
