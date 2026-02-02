# إضافة سبب الإلغاء للعميل - Complete

## ✅ Status: COMPLETE

تم إضافة خاصية سبب الإلغاء للعميل عند إلغاء الحجز بنجاح.

---

## 📋 التغييرات

### 1. Database Migration

✅ إضافة حقل `cancellation_reason` إلى جدول `bookings`

```php
$table->text('cancellation_reason')->nullable()->after('rejection_reason');
```

### 2. Booking Model

✅ إضافة `cancellation_reason` إلى:

- `$fillable` array
- `$casts` array

### 3. BookingDTO

✅ إضافة `cancellation_reason` إلى:

- Constructor parameters
- `fromModel()` method
- `toArray()` method
- `toIndexArray()` method

### 4. BookingService

✅ تعديل دالة `cancel()` لقبول `cancellation_reason`:

```php
public function cancel($id, string $reason = 'cancelled_by_customer', ?string $cancellationReason = null): bool
```

### 5. BookingController API

✅ تعديل `cancelByCustomer()` endpoint:

- إضافة validation لـ `cancellation_reason`
- السبب مطلوب (required)
- الحد الأدنى: 10 أحرف
- الحد الأقصى: 500 حرف

---

## 🔗 API Endpoint

### Cancel Booking by Customer

```http
POST /api/bookings/{id}/cancel-by-customer
```

**Headers:**

```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**

```json
{
    "cancellation_reason": "لدي ظرف طارئ ولا أستطيع الحضور في الموعد المحدد"
}
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "تم إلغاء الحجز بنجاح",
    "status_code": 200
}
```

**Validation Error (422):**

```json
{
    "message": "The cancellation reason field is required.",
    "errors": {
        "cancellation_reason": ["سبب الإلغاء مطلوب"]
    }
}
```

---

## 📝 Validation Rules

| Field                 | Rule     | Message                                   |
| --------------------- | -------- | ----------------------------------------- |
| `cancellation_reason` | required | سبب الإلغاء مطلوب                         |
| `cancellation_reason` | string   | -                                         |
| `cancellation_reason` | min:10   | سبب الإلغاء يجب أن يكون 10 أحرف على الأقل |
| `cancellation_reason` | max:500  | سبب الإلغاء يجب ألا يتجاوز 500 حرف        |

---

## 🎯 Use Cases

### 1. Customer Cancels Booking

```javascript
const cancelBooking = async (bookingId, reason) => {
    try {
        const response = await fetch(
            `/api/bookings/${bookingId}/cancel-by-customer`,
            {
                method: "POST",
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    cancellation_reason: reason,
                }),
            },
        );

        const data = await response.json();

        if (data.success) {
            console.log("Booking cancelled successfully");
        }
    } catch (error) {
        console.error("Error cancelling booking:", error);
    }
};

// Usage
cancelBooking(123, "لدي ظرف طارئ ولا أستطيع الحضور");
```

### 2. Display Cancellation Reason to Chef

```javascript
const BookingDetails = ({ booking }) => {
    return (
        <div>
            <h3>تفاصيل الحجز</h3>
            <p>الحالة: {booking.booking_status}</p>

            {booking.booking_status === "cancelled_by_customer" &&
                booking.cancellation_reason && (
                    <div className="cancellation-reason">
                        <h4>سبب الإلغاء من العميل:</h4>
                        <p>{booking.cancellation_reason}</p>
                    </div>
                )}

            {booking.booking_status === "rejected" &&
                booking.rejection_reason && (
                    <div className="rejection-reason">
                        <h4>سبب الرفض من الطاهي:</h4>
                        <p>{booking.rejection_reason}</p>
                    </div>
                )}
        </div>
    );
};
```

---

## 📊 Database Schema

```sql
ALTER TABLE bookings
ADD COLUMN cancellation_reason TEXT NULL
AFTER rejection_reason;
```

---

## 🔍 الفرق بين rejection_reason و cancellation_reason

| Field                 | Used By           | When            | Required |
| --------------------- | ----------------- | --------------- | -------- |
| `rejection_reason`    | الطاهي (Chef)     | عند رفض الحجز   | ✅ نعم   |
| `cancellation_reason` | العميل (Customer) | عند إلغاء الحجز | ✅ نعم   |

---

## ✨ Features

✅ **Validation** - التحقق من صحة السبب (10-500 حرف)
✅ **Required** - السبب مطلوب عند الإلغاء
✅ **Stored in DB** - يتم حفظ السبب في قاعدة البيانات
✅ **Visible to Chef** - الطاهي يمكنه رؤية سبب الإلغاء
✅ **API Response** - السبب يظهر في استجابة API

---

## 🚀 Ready to Use!

الخاصية جاهزة للاستخدام في التطبيق. العميل الآن ملزم بكتابة سبب الإلغاء عند إلغاء الحجز، والطاهي يمكنه رؤية السبب.
