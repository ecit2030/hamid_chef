# Admin Reports System Architecture

## Design Pattern: Repository-Service-DTO

تم تطبيق نمط Repository-Service-DTO بشكل كامل في نظام التقارير الإدارية.

## Structure

### 1. Controller Layer
**File**: `app/Http/Controllers/Admin/ReportController.php`

**المسؤولية**:
- استقبال الطلبات من المستخدم
- التحقق من المدخلات الأساسية
- استدعاء Service Layer
- إرجاع الاستجابة (Inertia Response أو File Download)

**لا يحتوي على**:
- أي استعلامات مباشرة للقاعدة البيانات
- أي منطق عمل (Business Logic)

### 2. Service Layer
**File**: `app/Services/AdminReportService.php`

**المسؤولية**:
- تنسيق العمليات بين عدة Repositories
- تطبيق منطق العمل (Business Logic)
- تجميع البيانات من مصادر متعددة
- معالجة البيانات قبل إرجاعها

**الميثودات**:
- `getBookingsReport()` - تقرير الحجوزات
- `getCustomersReport()` - تقرير العملاء
- `getChefsReport()` - تقرير الطهاة
- `getServicesReport()` - تقرير الخدمات
- `getEarningsReport()` - تقرير الأرباح
- `getTransactionsReport()` - تقرير المعاملات
- `get*ForExport()` - جلب البيانات للتصدير

### 3. Repository Layer

#### BookingRepository
**File**: `app/Repositories/BookingRepository.php`

**الميثودات الجديدة**:
- `getBookingsForReport()` - جلب الحجوزات مع pagination
- `getBookingsStats()` - إحصائيات الحجوزات
- `getBookingsForExport()` - جلب الحجوزات للتصدير
- `getDailyEarnings()` - الأرباح اليومية

#### UserRepository
**File**: `app/Repositories/UserRepository.php`

**الميثودات الجديدة**:
- `getCustomersForReport()` - جلب العملاء مع pagination
- `getCustomersStats()` - إحصائيات العملاء
- `getCustomersForExport()` - جلب العملاء للتصدير

#### ChefRepository
**File**: `app/Repositories/ChefRepository.php`

**الميثودات الجديدة**:
- `getChefsForReport()` - جلب الطهاة مع pagination
- `getChefsStats()` - إحصائيات الطهاة
- `getChefsForExport()` - جلب الطهاة للتصدير

#### ChefServiceRepository (New)
**File**: `app/Repositories/ChefServiceRepository.php`

**الميثودات**:
- `getServicesForReport()` - جلب الخدمات مع pagination
- `getServicesStats()` - إحصائيات الخدمات
- `getServicesForExport()` - جلب الخدمات للتصدير

#### ChefWalletTransactionRepository (New)
**File**: `app/Repositories/ChefWalletTransactionRepository.php`

**الميثودات**:
- `getTransactionsForReport()` - جلب المعاملات مع pagination
- `getTransactionsStats()` - إحصائيات المعاملات
- `getTransactionsForExport()` - جلب المعاملات للتصدير

### 4. Export Layer
**Files**: `app/Exports/Admin/*.php`

**المسؤولية**:
- تنسيق البيانات للتصدير إلى Excel
- تحديد الأعمدة والعناوين
- تطبيق التنسيقات

**الملفات**:
- `BookingsExport.php`
- `CustomersExport.php`
- `ChefsExport.php`
- `ServicesExport.php`
- `EarningsExport.php`
- `TransactionsExport.php`

## Data Flow

```
Request → Controller → Service → Repository → Database
                ↓
            Response
```

### مثال: تقرير الحجوزات

1. **Controller** يستقبل الطلب:
```php
public function bookings(Request $request): Response
{
    $data = $this->reportService->getBookingsReport($startDate, $status);
    return Inertia::render('Admin/Reports/Bookings', $data);
}
```

2. **Service** ينسق العملية:
```php
public function getBookingsReport(?Carbon $startDate, ?string $status): array
{
    $bookings = $this->bookingRepository->getBookingsForReport($startDate, $status);
    $stats = $this->bookingRepository->getBookingsStats($startDate, $status);
    
    return [
        'bookings' => $bookings,
        'stats' => $stats,
    ];
}
```

3. **Repository** يتعامل مع قاعدة البيانات:
```php
public function getBookingsForReport(?Carbon $startDate, ?string $status, int $perPage = 15)
{
    return Booking::with([...])
        ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
        ->when($status, fn($q) => $q->where('booking_status', $status))
        ->latest()
        ->paginate($perPage);
}
```

## Benefits

### 1. Separation of Concerns
- كل طبقة لها مسؤولية واحدة محددة
- سهولة الصيانة والتطوير

### 2. Testability
- يمكن اختبار كل طبقة بشكل منفصل
- سهولة عمل Mock للـ Dependencies

### 3. Reusability
- يمكن إعادة استخدام Repositories في أماكن أخرى
- Services يمكن استخدامها من Controllers مختلفة

### 4. Maintainability
- تغيير منطق قاعدة البيانات يتم في Repository فقط
- تغيير منطق العمل يتم في Service فقط
- Controller يبقى نظيفاً وبسيطاً

## Routes

جميع المسارات موجودة في `routes/admin.php`:

```php
Route::prefix('reports')->as('reports.')->group(function () {
    // View Reports
    Route::get('/bookings', [ReportController::class, 'bookings'])->name('bookings');
    Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
    Route::get('/chefs', [ReportController::class, 'chefs'])->name('chefs');
    Route::get('/services', [ReportController::class, 'services'])->name('services');
    Route::get('/earnings', [ReportController::class, 'earnings'])->name('earnings');
    Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');
    
    // Export Reports
    Route::get('/bookings/export', [ReportController::class, 'exportBookings'])->name('bookings.export');
    Route::get('/customers/export', [ReportController::class, 'exportCustomers'])->name('customers.export');
    Route::get('/chefs/export', [ReportController::class, 'exportChefs'])->name('chefs.export');
    Route::get('/services/export', [ReportController::class, 'exportServices'])->name('services.export');
    Route::get('/earnings/export', [ReportController::class, 'exportEarnings'])->name('earnings.export');
    Route::get('/transactions/export', [ReportController::class, 'exportTransactions'])->name('transactions.export');
});
```

## Next Steps

1. ✅ إنشاء Vue Components للتقارير
2. ✅ إنشاء PDF Templates
3. ✅ إضافة الترجمات
4. ✅ اختبار النظام
