# نظام الشروط والأحكام - Terms & Conditions System

## نظرة عامة

تم إنشاء نظام كامل لإدارة الشروط والأحكام مع الحفاظ على الـ Design Pattern المستخدم في المشروع.

## المميزات

✅ إدارة كاملة للشروط والأحكام من لوحة التحكم
✅ دعم اللغتين العربية والإنجليزية
✅ نظام الإصدارات (Versioning)
✅ تفعيل/تعطيل النسخ
✅ API للتطبيق الموبايل
✅ تاريخ السريان لكل نسخة
✅ الحفاظ على الـ Design Pattern (Repository, Service, DTO)

## البنية التقنية

### 1. Database Layer

**Migration**: `database/migrations/2026_02_01_154028_create_terms_and_conditions_table.php`

```sql
- id
- title_ar (العنوان بالعربية)
- title_en (العنوان بالإنجليزية)
- content_ar (المحتوى بالعربية)
- content_en (المحتوى بالإنجليزية)
- version (رقم الإصدار)
- is_active (نشط/غير نشط)
- effective_date (تاريخ السريان)
- created_by, updated_by
- timestamps, soft_deletes
```

**Model**: `app/Models/TermsAndConditions.php`

- يمتد من `BaseModel`
- يدعم Soft Deletes
- يدعم Activity Logging
- Accessors للحصول على المحتوى حسب اللغة

### 2. Repository Layer

**Repository**: `app/Repositories/TermsAndConditionsRepository.php`

Methods:

- `getActive()` - الحصول على النسخة النشطة
- `getAllVersions()` - الحصول على جميع الإصدارات

### 3. Service Layer

**Service**: `app/Services/TermsAndConditionsService.php`

Methods:

- `all()` - جميع الشروط والأحكام
- `paginate($perPage)` - مع ترقيم
- `find($id)` - البحث بالمعرف
- `getActive()` - النسخة النشطة
- `create($data)` - إنشاء جديد
- `update($id, $data)` - تحديث
- `delete($id)` - حذف
- `activate($id)` - تفعيل
- `deactivate($id)` - تعطيل

**Logic**: عند تفعيل نسخة جديدة، يتم تعطيل النسخة النشطة الحالية تلقائياً

### 4. DTO Layer

**DTO**: `app/DTOs/TermsAndConditionsDTO.php`

Methods:

- `fromModel($model)` - تحويل من Model
- `toArray()` - تحويل لمصفوفة كاملة
- `toLocalizedArray($locale)` - تحويل حسب اللغة (للموبايل)

### 5. Request Validation

**Requests**:

- `app/Http/Requests/StoreTermsAndConditionsRequest.php`
- `app/Http/Requests/UpdateTermsAndConditionsRequest.php`

Validation Rules:

- `title_ar`: required, string, max:255
- `title_en`: required, string, max:255
- `content_ar`: required, string
- `content_en`: required, string
- `version`: required, string, max:20
- `is_active`: nullable, boolean
- `effective_date`: nullable, date

### 6. Controllers

#### Admin Controller

**Path**: `app/Http/Controllers/Admin/TermsAndConditionsController.php`

Routes:

- `GET /admin/terms-and-conditions` - القائمة
- `GET /admin/terms-and-conditions/create` - صفحة الإنشاء
- `POST /admin/terms-and-conditions` - حفظ جديد
- `GET /admin/terms-and-conditions/{id}/edit` - صفحة التعديل
- `PUT /admin/terms-and-conditions/{id}` - تحديث
- `DELETE /admin/terms-and-conditions/{id}` - حذف
- `PATCH /admin/terms-and-conditions/{id}/activate` - تفعيل
- `PATCH /admin/terms-and-conditions/{id}/deactivate` - تعطيل

#### API Controller

**Path**: `app/Http/Controllers/Api/TermsAndConditionsController.php`

Routes:

- `GET /api/terms-and-conditions` - النسخة النشطة
- `GET /api/terms-and-conditions/versions` - جميع الإصدارات
- `GET /api/terms-and-conditions/{id}` - نسخة محددة

Query Parameters:

- `locale` - اللغة (ar/en) - افتراضي: ar

### 7. Frontend (Vue Components)

#### Admin Panel Components

**Index Page**: `resources/js/Pages/Admin/TermsAndConditions/Index.vue`

- عرض جميع الإصدارات
- جدول مع الإصدار، العنوان، التاريخ، الحالة
- أزرار: تعديل، تفعيل، تعطيل، حذف
- Pagination

**Create Page**: `resources/js/Pages/Admin/TermsAndConditions/Create.vue`

- نموذج إنشاء شروط وأحكام جديدة
- حقول: العنوان (عربي/إنجليزي)، المحتوى (عربي/إنجليزي)، الإصدار، تاريخ السريان
- Checkbox لتعيين كنسخة نشطة

**Edit Page**: `resources/js/Pages/Admin/TermsAndConditions/Edit.vue`

- نموذج تعديل شروط وأحكام موجودة
- نفس الحقول مع البيانات المحملة مسبقاً

## API Endpoints

### للتطبيق الموبايل

#### 1. الحصول على النسخة النشطة

```http
GET /api/terms-and-conditions?locale=ar
```

Response:

```json
{
    "success": true,
    "message": "تم جلب الشروط والأحكام بنجاح",
    "data": {
        "id": 1,
        "title": "الشروط والأحكام",
        "content": "محتوى الشروط والأحكام...",
        "version": "1.0",
        "effective_date": "2026-02-01T00:00:00.000000Z"
    }
}
```

#### 2. الحصول على جميع الإصدارات

```http
GET /api/terms-and-conditions/versions?locale=ar
```

Response:

```json
{
    "success": true,
    "message": "تم جلب جميع إصدارات الشروط والأحكام بنجاح",
    "data": [
        {
            "id": 2,
            "title": "الشروط والأحكام - الإصدار 2.0",
            "content": "...",
            "version": "2.0",
            "effective_date": "2026-02-01T00:00:00.000000Z"
        },
        {
            "id": 1,
            "title": "الشروط والأحكام - الإصدار 1.0",
            "content": "...",
            "version": "1.0",
            "effective_date": "2026-01-01T00:00:00.000000Z"
        }
    ]
}
```

#### 3. الحصول على نسخة محددة

```http
GET /api/terms-and-conditions/1?locale=en
```

Response:

```json
{
    "success": true,
    "message": "تم جلب الشروط والأحكام بنجاح",
    "data": {
        "id": 1,
        "title": "Terms and Conditions",
        "content": "Terms and conditions content...",
        "version": "1.0",
        "effective_date": "2026-02-01T00:00:00.000000Z"
    }
}
```

## استخدام النظام

### من لوحة التحكم

1. **الوصول للنظام**:
    - انتقل إلى `/admin/terms-and-conditions`

2. **إنشاء شروط وأحكام جديدة**:
    - اضغط على "إضافة شروط وأحكام جديدة"
    - املأ العنوان والمحتوى بالعربية والإنجليزية
    - حدد رقم الإصدار (مثل: 1.0, 2.0)
    - اختر تاريخ السريان
    - فعّل "تعيين كنسخة نشطة" إذا أردت تفعيلها مباشرة
    - احفظ

3. **تعديل شروط وأحكام موجودة**:
    - اضغط على "تعديل" بجانب النسخة المطلوبة
    - عدّل البيانات
    - احفظ

4. **تفعيل/تعطيل نسخة**:
    - اضغط على "تفعيل" لتفعيل نسخة (سيتم تعطيل النسخة النشطة الحالية)
    - اضغط على "تعطيل" لتعطيل نسخة

5. **حذف نسخة**:
    - اضغط على "حذف" (Soft Delete)

### من التطبيق الموبايل

```dart
// مثال Flutter
Future<TermsAndConditions> getTermsAndConditions() async {
  final response = await http.get(
    Uri.parse('https://api.example.com/api/terms-and-conditions?locale=ar'),
  );

  if (response.statusCode == 200) {
    final data = json.decode(response.body);
    return TermsAndConditions.fromJson(data['data']);
  }
  throw Exception('Failed to load terms and conditions');
}
```

## الترجمات

تم إضافة الترجمات في `resources/js/locales/ar.json`:

```json
{
    "terms_and_conditions": {
        "title": "الشروط والأحكام",
        "create_new": "إضافة شروط وأحكام جديدة",
        "edit": "تعديل الشروط والأحكام",
        "version": "الإصدار",
        "title_ar": "العنوان بالعربية",
        "title_en": "العنوان بالإنجليزية",
        "content_ar": "المحتوى بالعربية",
        "content_en": "المحتوى بالإنجليزية",
        "effective_date": "تاريخ السريان",
        "set_as_active": "تعيين كنسخة نشطة"
    }
}
```

## الملفات المنشأة

### Backend

1. `database/migrations/2026_02_01_154028_create_terms_and_conditions_table.php`
2. `app/Models/TermsAndConditions.php`
3. `app/Repositories/TermsAndConditionsRepository.php`
4. `app/Services/TermsAndConditionsService.php`
5. `app/DTOs/TermsAndConditionsDTO.php`
6. `app/Http/Requests/StoreTermsAndConditionsRequest.php`
7. `app/Http/Requests/UpdateTermsAndConditionsRequest.php`
8. `app/Http/Controllers/Admin/TermsAndConditionsController.php`
9. `app/Http/Controllers/Api/TermsAndConditionsController.php`

### Frontend

10. `resources/js/Pages/Admin/TermsAndConditions/Index.vue`
11. `resources/js/Pages/Admin/TermsAndConditions/Create.vue`
12. `resources/js/Pages/Admin/TermsAndConditions/Edit.vue`

### Routes

- تم تحديث `routes/api.php`
- تم تحديث `routes/admin.php`

### Translations

- تم تحديث `resources/js/locales/ar.json`

## Design Pattern المستخدم

النظام يتبع نفس الـ Design Pattern المستخدم في المشروع:

```
Request → Controller → Service → Repository → Model
                ↓
              DTO → Response
```

### مميزات الـ Pattern:

1. **Separation of Concerns**: كل طبقة لها مسؤولية واحدة
2. **Testability**: سهولة كتابة Unit Tests
3. **Maintainability**: سهولة الصيانة والتطوير
4. **Reusability**: إعادة استخدام الكود
5. **Consistency**: توحيد الأسلوب في المشروع

## الخطوات التالية (اختيارية)

1. **إضافة Rich Text Editor**: استخدام TinyMCE أو Quill لتنسيق المحتوى
2. **إضافة Preview**: معاينة الشروط والأحكام قبل الحفظ
3. **إضافة History**: سجل التغييرات على كل نسخة
4. **إضافة Notifications**: إشعار المستخدمين عند تحديث الشروط
5. **إضافة Acceptance Tracking**: تتبع موافقة المستخدمين على الشروط

## التاريخ

تم الإنشاء: 1 فبراير 2026
