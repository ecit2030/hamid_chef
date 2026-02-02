# اختبار API إلغاء الحجز من العميل
# استخدام: .\test_cancel_booking.ps1

# تعديل هذه المتغيرات حسب بيئتك
$API_URL = "http://localhost:8000/api"
$EMAIL = "test.user@example.com"
$PASSWORD = "password"
$BOOKING_ID = "1"

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "اختبار API إلغاء الحجز من العميل" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

# الخطوة 1: تسجيل الدخول
Write-Host "1. تسجيل الدخول..." -ForegroundColor Yellow

$loginBody = @{
    email = $EMAIL
    password = $PASSWORD
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "$API_URL/login" `
        -Method Post `
        -Headers @{
            "Content-Type" = "application/json"
            "Accept" = "application/json"
        } `
        -Body $loginBody

    Write-Host "استجابة تسجيل الدخول:" -ForegroundColor Green
    $loginResponse | ConvertTo-Json -Depth 10
    Write-Host ""

    # استخراج الـ token
    $TOKEN = $null
    if ($loginResponse.data.token) {
        $TOKEN = $loginResponse.data.token
    } elseif ($loginResponse.token) {
        $TOKEN = $loginResponse.token
    }

    if (-not $TOKEN) {
        Write-Host "❌ فشل تسجيل الدخول - لم يتم الحصول على token" -ForegroundColor Red
        Write-Host "تحقق من البريد الإلكتروني وكلمة المرور" -ForegroundColor Red
        exit 1
    }

    Write-Host "✅ تم تسجيل الدخول بنجاح" -ForegroundColor Green
    Write-Host "Token: $($TOKEN.Substring(0, [Math]::Min(20, $TOKEN.Length)))..." -ForegroundColor Green
    Write-Host ""

    # الخطوة 2: إلغاء الحجز
    Write-Host "2. إلغاء الحجز رقم ${BOOKING_ID}..." -ForegroundColor Yellow

    $cancelBody = @{
        cancellation_reason = "اختبار إلغاء الحجز - اضطررت للسفر بشكل مفاجئ ولن أتمكن من الحضور"
    } | ConvertTo-Json

    $cancelResponse = Invoke-RestMethod -Uri "$API_URL/bookings/$BOOKING_ID/cancel-by-customer" `
        -Method Post `
        -Headers @{
            "Content-Type" = "application/json"
            "Accept" = "application/json"
            "Authorization" = "Bearer $TOKEN"
        } `
        -Body $cancelBody

    Write-Host "استجابة إلغاء الحجز:" -ForegroundColor Green
    $cancelResponse | ConvertTo-Json -Depth 10
    Write-Host ""

    # التحقق من النتيجة
    if ($cancelResponse.success -eq $true) {
        Write-Host "✅ تم إلغاء الحجز بنجاح!" -ForegroundColor Green
    } else {
        Write-Host "❌ فشل إلغاء الحجز" -ForegroundColor Red
        if ($cancelResponse.message) {
            Write-Host "رسالة الخطأ: $($cancelResponse.message)" -ForegroundColor Red
        }
    }

} catch {
    Write-Host "❌ حدث خطأ:" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red

    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "تفاصيل الخطأ:" -ForegroundColor Red
        Write-Host $responseBody -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "انتهى الاختبار" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
