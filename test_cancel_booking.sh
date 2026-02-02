#!/bin/bash

# اختبار API إلغاء الحجز من العميل
# استخدام: bash test_cancel_booking.sh

# تعديل هذه المتغيرات حسب بيئتك
API_URL="http://localhost:8000/api"
EMAIL="test.user@example.com"
PASSWORD="password"
BOOKING_ID="1"

echo "========================================="
echo "اختبار API إلغاء الحجز من العميل"
echo "========================================="
echo ""

# الخطوة 1: تسجيل الدخول
echo "1. تسجيل الدخول..."
LOGIN_RESPONSE=$(curl -s -X POST "${API_URL}/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"email\":\"${EMAIL}\",\"password\":\"${PASSWORD}\"}")

echo "استجابة تسجيل الدخول:"
echo "$LOGIN_RESPONSE" | jq '.'
echo ""

# استخراج الـ token
TOKEN=$(echo "$LOGIN_RESPONSE" | jq -r '.data.token // .token // empty')

if [ -z "$TOKEN" ] || [ "$TOKEN" = "null" ]; then
  echo "❌ فشل تسجيل الدخول - لم يتم الحصول على token"
  echo "تحقق من البريد الإلكتروني وكلمة المرور"
  exit 1
fi

echo "✅ تم تسجيل الدخول بنجاح"
echo "Token: ${TOKEN:0:20}..."
echo ""

# الخطوة 2: إلغاء الحجز
echo "2. إلغاء الحجز رقم ${BOOKING_ID}..."
CANCEL_RESPONSE=$(curl -s -X POST "${API_URL}/bookings/${BOOKING_ID}/cancel-by-customer" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer ${TOKEN}" \
  -d '{"cancellation_reason":"اختبار إلغاء الحجز - اضطررت للسفر بشكل مفاجئ ولن أتمكن من الحضور"}')

echo "استجابة إلغاء الحجز:"
echo "$CANCEL_RESPONSE" | jq '.'
echo ""

# التحقق من النتيجة
SUCCESS=$(echo "$CANCEL_RESPONSE" | jq -r '.success // empty')

if [ "$SUCCESS" = "true" ]; then
  echo "✅ تم إلغاء الحجز بنجاح!"
else
  echo "❌ فشل إلغاء الحجز"
  ERROR_MESSAGE=$(echo "$CANCEL_RESPONSE" | jq -r '.message // empty')
  if [ ! -z "$ERROR_MESSAGE" ]; then
    echo "رسالة الخطأ: $ERROR_MESSAGE"
  fi
fi

echo ""
echo "========================================="
echo "انتهى الاختبار"
echo "========================================="
