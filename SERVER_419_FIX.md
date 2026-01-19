# حل مشكلة 419 Page Expired على السيرفر

## المشكلة
عند محاولة تسجيل الدخول على السيرفر، يظهر خطأ **419 Page Expired** بسبب مشكلة في CSRF Token.

## ✅ الحل الأساسي (تم تطبيقه)

تم إصلاح ملف `resources/js/bootstrap.js` لإضافة CSRF token configuration.

**يجب عليك الآن:**
1. رفع ملف `resources/js/bootstrap.js` الجديد للسيرفر
2. رفع مجلد `public/build/` الجديد للسيرفر (تم بناؤه بنجاح)
3. تطبيق الحلول التالية على السيرفر

## الحلول المطلوبة على السيرفر (بالترتيب)

### 1. تحديث إعدادات `.env` على السيرفر

أضف أو عدّل هذه الإعدادات في ملف `.env` على السيرفر:

```env
# Session Settings
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_DOMAIN=.monchef.codebrains.net
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# App Settings
APP_URL=https://monchef.codebrains.net

# Sanctum (إذا كنت تستخدمه)
SANCTUM_STATEFUL_DOMAINS=monchef.codebrains.net
```

### 2. التأكد من جدول Sessions في قاعدة البيانات

شغل هذه الأوامر على السيرفر:

```bash
# إنشاء migration لجدول sessions
php artisan session:table

# تشغيل الـ migration
php artisan migrate
```

### 3. مسح جميع أنواع الكاش

```bash
# مسح الكاش
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# إعادة بناء الكاش
php artisan config:cache
php artisan route:cache
```

### 4. التأكد من صلاحيات المجلدات

```bash
# إعطاء صلاحيات للمجلدات المطلوبة
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# تغيير المالك (استبدل www-data بمستخدم الويب سيرفر لديك)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### 5. التحقق من إعدادات الويب سيرفر

#### إذا كنت تستخدم Apache:
تأكد من تفعيل mod_rewrite:
```bash
a2enmod rewrite
systemctl restart apache2
```

#### إذا كنت تستخدم Nginx:
تأكد من إعدادات الـ location في ملف الـ config:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 6. حل مشاكل Cloudflare/CDN (إن وجدت)

إذا كنت تستخدم Cloudflare:
1. اذهب إلى **SSL/TLS** → اختر **Full** أو **Full (strict)**
2. في **Page Rules**، أضف قاعدة لـ `/admin/*` و `/chef/*`:
   - Disable Performance
   - Disable Cache

### 7. التحقق من Trusted Proxies

إذا كنت خلف Load Balancer أو Cloudflare، عدّل ملف `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->trustProxies(at: '*');
    // ... باقي الكود
})
```

### 8. زيادة Session Lifetime (اختياري)

في `.env`:
```env
SESSION_LIFETIME=720  # 12 ساعة بدلاً من 2
```

## اختبار الحل

بعد تطبيق الحلول:

1. امسح كاش المتصفح أو افتح نافذة خاصة (Incognito)
2. اذهب لصفحة اللوجن
3. افتح Developer Tools (F12) → Network Tab
4. حاول تسجيل الدخول
5. تحقق من:
   - وجود Cookie باسم `laravel_session` أو `monchef-session`
   - وجود Header: `X-CSRF-TOKEN` في الـ request

## الأسباب الشائعة

- ✅ مجلد `storage` لا يملك صلاحيات الكتابة
- ✅ جدول `sessions` غير موجود في قاعدة البيانات
- ✅ إعدادات SESSION_DOMAIN خاطئة
- ✅ الكاش القديم لم يتم مسحه
- ✅ Cloudflare يحظر الـ cookies
- ✅ HTTPS غير مفعل بشكل صحيح

## التحقق من المشكلة

شغل هذا الأمر على السيرفر للتحقق:

```bash
# التحقق من صلاحيات storage
ls -la storage/

# التحقق من جدول sessions
php artisan tinker
>>> DB::table('sessions')->count();

# التحقق من الإعدادات
php artisan config:show session
```

## ملاحظات مهمة

1. **لا تنسى** مسح الكاش بعد أي تغيير في `.env`
2. **تأكد** من رفع ملفات `public/build/` الجديدة
3. **استخدم** نافذة خاصة للاختبار لتجنب مشاكل الكاش
4. **تحقق** من أن `APP_KEY` موجود في `.env`

## إذا استمرت المشكلة

جرب هذا الحل المؤقت (للتطوير فقط):

في `app/Http/Middleware/VerifyCsrfToken.php`:

```php
protected $except = [
    // 'chef/login',  // لا تستخدم هذا في الإنتاج!
];
```

**تحذير:** هذا الحل غير آمن ولا يُنصح به في الإنتاج!
