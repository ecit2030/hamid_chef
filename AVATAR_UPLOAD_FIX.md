# إصلاح مشكلة رفع صورة الملف الشخصي (Avatar)

## المشكلة

كان هناك فشل في تحديث صورة الملف الشخصي (avatar) للمستخدمين والطهاة عبر API.

## الحلول المطبقة

### 1. إصلاح ChefServiceRepository

- **المشكلة**: `ChefServiceRepository` لم يكن يرث من `BaseRepository`، مما تسبب في خطأ "Call to undefined method create()"
- **الحل**: جعل `ChefServiceRepository` يرث من `BaseRepository` وإضافة العلاقات الافتراضية

```php
class ChefServiceRepository extends BaseRepository
{
    protected array $defaultWith = [
        'chef.user',
        'tags',
        'images',
        'equipment',
        'category'
    ];
}
```

### 2. إضافة دعم Avatar في طلبات التحديث

#### UpdateUserProfileRequest

```php
'avatar' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
```

#### UpdateChefProfileRequest

```php
'avatar' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
```

### 3. تحديث UserService

- إزالة استخدام دوال `uploadFile` و `deleteFile` غير الموجودة
- الاعتماد على `BaseRepository` لمعالجة رفع الملفات تلقائياً

```php
public function updateProfile($user, array $data)
{
    // Remove password if empty
    if (array_key_exists('password', $data) && empty($data['password'])) {
        unset($data['password']);
    }

    // BaseRepository will handle avatar upload automatically
    return $this->users->update($user->id, $data);
}
```

### 4. تحديث ChefService

- تبسيط دالة `updateChefProfile` للاعتماد على `BaseRepository`
- إزالة معالجة الملفات اليدوية

### 5. تحديث ChefProfileController

- إضافة معالجة منفصلة لحقول المستخدم (avatar) وحقول الطاهي
- إضافة Transaction للتأكد من تحديث كلا الجدولين بنجاح
- معالجة رفع الملفات يدوياً لجدول users

```php
// Separate user data from chef data
$userFields = ['avatar'];

// Handle avatar upload for user table
if ($value instanceof \Illuminate\Http\UploadedFile) {
    $filename = Str::uuid() . '.' . $value->getClientOriginalExtension();
    $path = $value->storeAs('users', $filename, 'public');

    // Delete old file if exists
    if ($user->$key && Storage::disk('public')->exists($user->$key)) {
        Storage::disk('public')->delete($user->$key);
    }

    $userData[$key] = $path;
}
```

## كيفية الاستخدام

### API للمستخدم العادي

```bash
POST /api/profile
Content-Type: multipart/form-data

avatar: [file]
first_name: "أحمد"
last_name: "محمد"
```

### API للطاهي

```bash
POST /api/chef/profile
Content-Type: multipart/form-data

avatar: [file]
name: "الطاهي أحمد"
bio: "طاهي محترف"
```

## الملفات المعدلة

1. `app/Repositories/ChefServiceRepository.php` - إضافة BaseRepository
2. `app/Http/Requests/UpdateUserProfileRequest.php` - إضافة قاعدة avatar
3. `app/Http/Requests/UpdateChefProfileRequest.php` - إضافة قاعدة avatar
4. `app/Services/UserService.php` - تبسيط معالجة الملفات
5. `app/Services/ChefService.php` - تبسيط معالجة الملفات
6. `app/Http/Controllers/Api/ChefProfileController.php` - معالجة avatar للمستخدم

## ملاحظات

- صورة Avatar تُخزن في `storage/app/public/users/`
- الحد الأقصى لحجم الصورة: 2MB
- الصيغ المدعومة: jpeg, png, jpg, gif
- يتم حذف الصورة القديمة تلقائياً عند رفع صورة جديدة
- يتم إرجاع رابط الصورة في API Response عبر `UserResource` و `ChefResource`

## التاريخ

21 يناير 2026
