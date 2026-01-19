# ✅ Server Deployment Checklist - Fix 419 Error

## 📦 Files to Upload

- [ ] `resources/js/bootstrap.js` (تم تحديثه)
- [ ] `public/build/` (المجلد كامل - تم بناؤه)
- [ ] `fix-419-server.sh` (سكريبت الإصلاح)

## 🔧 Commands to Run on Server

```bash
# 1. Upload files first, then run:
chmod +x fix-419-server.sh
./fix-419-server.sh
```

## ⚙️ .env Settings to Update

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_DOMAIN=.monchef.codebrains.net
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
APP_URL=https://monchef.codebrains.net
SANCTUM_STATEFUL_DOMAINS=monchef.codebrains.net
```

## 🧪 Testing Steps

1. [ ] Clear browser cache or use Incognito mode
2. [ ] Go to: https://monchef.codebrains.net/chef/login
3. [ ] Open Developer Tools (F12) → Network tab
4. [ ] Try to login
5. [ ] Check for:
   - [ ] Cookie: `monchef-session` or `laravel_session`
   - [ ] Header: `X-CSRF-TOKEN` in POST request
   - [ ] Response: Should be 302 (redirect) not 419

## 🔍 Troubleshooting

If still getting 419:

```bash
# Check sessions table exists
php artisan tinker
>>> DB::table('sessions')->count();

# Check storage permissions
ls -la storage/

# Check session config
php artisan config:show session

# Check logs
tail -f storage/logs/laravel.log
```

## 📝 Notes

- تأكد من رفع `public/build/` الجديد (الملف الآن: `app-B0bOjJaZ.js`)
- استخدم نافذة خاصة للاختبار
- إذا استمرت المشكلة، تحقق من Cloudflare settings
