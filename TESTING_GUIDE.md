# دليل الاختبارات - تحديث المستخدم والطاهي وإضافة الخدمات

## نظرة عامة

تم إنشاء مجموعة شاملة من الاختبارات لضمان عمل الوظائف التالية بشكل صحيح:

1. تحديث ملف المستخدم مع رفع Avatar
2. تحديث ملف الطاهي مع رفع Avatar
3. إضافة خدمة جديدة للطاهي

## ملفات الاختبار

### 1. UserProfileUpdateTest.php

اختبارات تحديث ملف المستخدم العادي

**الاختبارات المتضمنة:**

- ✅ تحديث البيانات الأساسية (الاسم، الهاتف، العنوان)
- ✅ رفع صورة Avatar جديدة
- ✅ تحديث صورة Avatar موجودة (حذف القديمة)
- ✅ التحقق من صيغة الصورة (يجب أن تكون image)
- ✅ التحقق من حجم الصورة (حد أقصى 2MB)
- ✅ تحديث الملف بدون Avatar
- ✅ التحقق من فرادة البريد الإلكتروني
- ✅ التحقق من فرادة رقم الهاتف
- ✅ منع المستخدم غير المصادق من التحديث
- ✅ عرض ملف المستخدم

### 2. ChefProfileUpdateTest.php

اختبارات تحديث ملف الطاهي

**الاختبارات المتضمنة:**

- ✅ تحديث بيانات الطاهي (الاسم، السيرة الذاتية، الوصف)
- ✅ رفع صورة Avatar للطاهي
- ✅ تحديث بيانات الموقع (المحافظة، المديرية، المنطقة)
- ✅ تحديث السعر الأساسي بالساعة
- ✅ منع المستخدم العادي من تحديث ملف الطاهي
- ✅ التعامل مع الطاهي بدون ملف شخصي (404)
- ✅ عرض ملف الطاهي
- ✅ التحقق من أن السعر يجب أن يكون موجباً
- ✅ التحقق من الحد الأقصى للسعر
- ✅ منع المستخدم غير المصادق

### 3. ChefServiceCreationTest.php

اختبارات إضافة خدمة جديدة

**الاختبارات المتضمنة:**

- ✅ إنشاء خدمة أساسية
- ✅ إنشاء خدمة مع صورة مميزة
- ✅ إنشاء خدمة مع علامات (Tags)
- ✅ إنشاء خدمة مع معدات (Equipment)
- ✅ إنشاء خدمة مع معرض صور
- ✅ إنشاء خدمة باقة (Package)
- ✅ التحقق من أن الاسم مطلوب
- ✅ التحقق من صحة نوع الخدمة
- ✅ التحقق من السعر بالساعة للخدمة الساعية
- ✅ التحقق من سعر الباقة لخدمة الباقة
- ✅ منع المستخدم العادي من إنشاء خدمة
- ✅ منع المستخدم غير المصادق
- ✅ إنشاء Slug تلقائياً
- ✅ القيمة الافتراضية لساعات الراحة

## تشغيل الاختبارات

### تشغيل جميع الاختبارات

```bash
php artisan test
```

### تشغيل اختبارات محددة

#### اختبارات تحديث المستخدم

```bash
php artisan test tests/Feature/Api/UserProfileUpdateTest.php
```

#### اختبارات تحديث الطاهي

```bash
php artisan test tests/Feature/Api/ChefProfileUpdateTest.php
```

#### اختبارات إضافة الخدمة

```bash
php artisan test tests/Feature/Api/ChefServiceCreationTest.php
```

### تشغيل اختبار واحد محدد

```bash
php artisan test --filter test_user_can_upload_avatar
```

### تشغيل مع تقرير مفصل

```bash
php artisan test --testdox
```

### تشغيل مع تغطية الكود

```bash
php artisan test --coverage
```

## متطلبات الاختبارات

### 1. قاعدة البيانات

- يتم استخدام قاعدة بيانات اختبار منفصلة
- يتم إعادة بناء القاعدة بعد كل اختبار (RefreshDatabase)

### 2. التخزين

- يتم استخدام Storage Fake لمحاكاة رفع الملفات
- لا يتم رفع ملفات حقيقية أثناء الاختبارات

### 3. المصادقة

- يتم استخدام Sanctum للمصادقة
- يتم إنشاء مستخدمين وهميين لكل اختبار

## أمثلة على الاستخدام

### مثال 1: تحديث ملف المستخدم مع Avatar

```php
$user = User::factory()->create(['user_type' => 'customer']);
$avatar = UploadedFile::fake()->image('avatar.jpg', 200, 200);

$response = $this->actingAs($user, 'sanctum')
    ->postJson('/api/profile', [
        'first_name' => 'أحمد',
        'avatar' => $avatar,
    ]);

$response->assertStatus(200);
```

### مثال 2: تحديث ملف الطاهي

```php
$user = User::factory()->create(['user_type' => 'chef']);
$chef = Chef::factory()->create(['user_id' => $user->id]);

$response = $this->actingAs($user, 'sanctum')
    ->postJson('/api/chef/profile', [
        'name' => 'الطاهي محمد',
        'bio' => 'طاهي متخصص',
        'base_hourly_rate' => 150.00,
    ]);

$response->assertStatus(200);
```

### مثال 3: إضافة خدمة جديدة

```php
$user = User::factory()->create(['user_type' => 'chef']);
$chef = Chef::factory()->create(['user_id' => $user->id]);

$response = $this->actingAs($user, 'sanctum')
    ->postJson('/api/chef/services', [
        'name' => 'خدمة الطهي المنزلي',
        'description' => 'خدمة طهي احترافية',
        'service_type' => 'hourly',
        'hourly_rate' => 150.00,
        'min_hours' => 3,
    ]);

$response->assertStatus(201);
```

## التحقق من النتائج

### التحقق من قاعدة البيانات

```php
$this->assertDatabaseHas('users', [
    'id' => $user->id,
    'first_name' => 'أحمد',
]);
```

### التحقق من رفع الملفات

```php
Storage::disk('public')->assertExists($user->avatar);
```

### التحقق من حذف الملفات القديمة

```php
Storage::disk('public')->assertMissing($oldAvatarPath);
```

### التحقق من استجابة JSON

```php
$response->assertStatus(200)
    ->assertJsonStructure([
        'message',
        'data' => ['id', 'name', 'avatar'],
    ]);
```

## معالجة الأخطاء

### أخطاء التحقق (Validation Errors)

```php
$response->assertStatus(422)
    ->assertJsonValidationErrors(['avatar']);
```

### أخطاء المصادقة

```php
$response->assertStatus(401);
```

### أخطاء الصلاحيات

```php
$response->assertStatus(403);
```

### أخطاء عدم العثور

```php
$response->assertStatus(404);
```

## نصائح للاختبار

1. **استخدم Factory** لإنشاء البيانات الوهمية
2. **استخدم Storage::fake()** لمحاكاة رفع الملفات
3. **استخدم RefreshDatabase** لضمان نظافة قاعدة البيانات
4. **اختبر الحالات الإيجابية والسلبية** لكل وظيفة
5. **تحقق من الاستجابات والبيانات المخزنة** في كل اختبار

## الإحصائيات

- **إجمالي الاختبارات**: 35 اختبار
- **اختبارات تحديث المستخدم**: 11 اختبار
- **اختبارات تحديث الطاهي**: 11 اختبار
- **اختبارات إضافة الخدمة**: 13 اختبار

## التاريخ

21 يناير 2026
