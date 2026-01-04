# Working Hours Page Redesign

## Overview
تم إعادة تصميم صفحة ساعات العمل بتصميم عصري وأنيق مع واجهة مستخدم محسّنة وميزات جديدة.

## New Design Features

### 1. **Card-Based Layout** 🎴
- كل يوم يظهر في بطاقة منفصلة أنيقة
- تصميم responsive يتكيف مع جميع الشاشات
- Grid layout: 4 أعمدة على الشاشات الكبيرة، 3 على المتوسطة، 2 على الصغيرة، 1 على الموبايل

### 2. **Visual Day Indicators** 🎨
- أيقونات emoji مميزة لكل يوم:
  - الأحد: ☀️ (يوم الشمس)
  - الإثنين-الخميس: 💼 (أيام العمل)
  - الجمعة: 🕌 (يوم الجمعة)
  - السبت: 🎉 (يوم الراحة)
- ألوان مميزة للأيام النشطة (gradient أزرق)
- ألوان رمادية للأيام المعطلة

### 3. **Auto-Save Functionality** 💾
- حفظ تلقائي عند أي تغيير (بعد ثانية واحدة)
- مؤشر "جاري الحفظ..." أثناء الحفظ
- رسالة "تم الحفظ في XX:XX" بعد النجاح
- لا حاجة للضغط على زر حفظ يدوياً

### 4. **Duration Calculator** ⏱️
- حساب تلقائي لمدة العمل
- يظهر بالساعات والدقائق
- مثال: "8 ساعات 30 دقائق"

### 5. **Quick Actions** ⚡
- **نسخ لجميع الأيام**: نسخ أوقات يوم معين لجميع الأيام النشطة
- **تفعيل جميع الأيام**: تفعيل كل الأيام بأوقات افتراضية (9 صباحاً - 5 مساءً)
- **تعطيل جميع الأيام**: تعطيل كل الأيام
- **ساعات العمل الرسمية**: تفعيل الأحد-الخميس (9-5)، تعطيل الجمعة والسبت

### 6. **Enhanced UX** ✨
- Toggle switch أنيق لتفعيل/تعطيل كل يوم
- Time inputs مع أيقونات ساعة
- رسالة "يوم عطلة" للأيام المعطلة
- Hover effects على البطاقات
- زر "نسخ" يظهر عند hover على البطاقة النشطة

### 7. **Bilingual Support** 🌐
- أسماء الأيام بالعربية والإنجليزية
- واجهة كاملة بالعربية
- دعم RTL

## Technical Implementation

### Frontend Changes

#### New Component Structure
```vue
<template>
  <!-- Days Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    <div v-for="hour in form.working_hours" class="group relative">
      <!-- Day Card with gradient background when active -->
      <div class="rounded-2xl border-2 transition-all">
        <!-- Day Header with emoji and toggle -->
        <!-- Time Inputs (only shown when active) -->
        <!-- Duration Display -->
        <!-- Quick Copy Button (on hover) -->
      </div>
    </div>
  </div>
  
  <!-- Quick Actions Bar -->
  <div class="flex items-center justify-between">
    <!-- Bulk action buttons -->
    <!-- Save status indicator -->
  </div>
</template>
```

#### Key Functions

**Auto-Save**
```javascript
const autoSave = () => {
  clearTimeout(saveTimeout)
  saveTimeout = setTimeout(() => {
    submit()
  }, 1000)
}
```

**Duration Calculator**
```javascript
const calculateDuration = (start, end) => {
  const [startHour, startMin] = start.split(':').map(Number)
  const [endHour, endMin] = end.split(':').map(Number)
  
  let hours = endHour - startHour
  let minutes = endMin - startMin
  
  if (minutes < 0) {
    hours--
    minutes += 60
  }
  
  return `${hours} ساعات ${minutes > 0 ? `${minutes} دقائق` : ''}`
}
```

**Copy to All Days**
```javascript
const copyToAll = (sourceIndex) => {
  const source = form.working_hours[sourceIndex]
  form.working_hours.forEach((hour, index) => {
    if (index !== sourceIndex && hour.is_active) {
      hour.start_time = source.start_time
      hour.end_time = source.end_time
    }
  })
  autoSave()
}
```

**Set Business Hours**
```javascript
const setBusinessHours = () => {
  form.working_hours.forEach((hour, index) => {
    // Enable Sunday to Thursday (0-4)
    if (index >= 0 && index <= 4) {
      hour.is_active = true
      hour.start_time = '09:00'
      hour.end_time = '17:00'
    } else {
      hour.is_active = false
    }
  })
  autoSave()
}
```

### Styling Features

**Active Day Card**
```css
border-brand-500 
bg-gradient-to-br from-brand-50 to-white 
dark:from-brand-900/20 dark:to-gray-800 
shadow-lg shadow-brand-500/20
```

**Inactive Day Card**
```css
border-gray-200 dark:border-gray-700 
bg-white dark:bg-gray-800 
hover:border-gray-300 dark:hover:border-gray-600
```

**Day Icon (Active)**
```css
bg-brand-500 text-white 
shadow-lg shadow-brand-500/30
```

**Day Icon (Inactive)**
```css
bg-gray-100 dark:bg-gray-700 
text-gray-400
```

## New Translation Keys

### Arabic (ar.json)
```json
{
  "chef": {
    "day_off": "يوم عطلة",
    "duration": "المدة",
    "hours": "ساعات",
    "minutes": "دقائق",
    "copy_to_all": "نسخ لجميع الأيام",
    "enable_all_days": "تفعيل جميع الأيام",
    "disable_all_days": "تعطيل جميع الأيام",
    "set_business_hours": "ساعات العمل الرسمية",
    "saved_at": "تم الحفظ في"
  }
}
```

## User Experience Flow

### Scenario 1: Setting Up Working Hours for First Time
1. الشيف يفتح صفحة ساعات العمل
2. يرى 7 بطاقات (واحدة لكل يوم)
3. الأيام الافتراضية نشطة: الإثنين-الخميس (9 صباحاً - 5 مساءً)
4. يمكنه تعديل أي يوم بالضغط على toggle أو تغيير الأوقات
5. التغييرات تُحفظ تلقائياً

### Scenario 2: Quick Setup with Business Hours
1. الشيف يضغط "ساعات العمل الرسمية"
2. يتم تفعيل الأحد-الخميس (9-5)
3. يتم تعطيل الجمعة والسبت
4. يُحفظ تلقائياً

### Scenario 3: Copying Hours to All Days
1. الشيف يعدل أوقات يوم الأحد (10 صباحاً - 6 مساءً)
2. يضع الماوس على بطاقة الأحد
3. يظهر زر "نسخ"
4. يضغط عليه
5. تُنسخ الأوقات لجميع الأيام النشطة
6. يُحفظ تلقائياً

### Scenario 4: Disabling a Day
1. الشيف يريد تعطيل يوم الجمعة
2. يضغط على toggle في بطاقة الجمعة
3. تتحول البطاقة للون الرمادي
4. تظهر رسالة "يوم عطلة"
5. يُحفظ تلقائياً

## Responsive Design

### Desktop (xl: 1280px+)
- 4 columns grid
- Full card details visible
- Hover effects enabled

### Laptop (lg: 1024px+)
- 3 columns grid
- Full card details visible

### Tablet (md: 768px+)
- 2 columns grid
- Full card details visible

### Mobile (< 768px)
- 1 column grid
- Stacked cards
- Touch-friendly buttons

## Files Modified

### Frontend
- `resources/js/Pages/Chef/WorkingHours/Index.vue`
  - Complete redesign with card-based layout
  - Added auto-save functionality
  - Added duration calculator
  - Added quick action buttons
  - Added copy to all feature
  - Enhanced visual design

### Translations
- `resources/js/locales/ar.json`
  - Added new translation keys for working hours features

## Browser Compatibility
- ✅ Chrome/Edge (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## Performance
- Auto-save debounced (1 second delay)
- Minimal re-renders
- Smooth transitions and animations
- Optimized for mobile devices

## Future Enhancements
- [ ] Add preset templates (e.g., "Morning shift", "Evening shift")
- [ ] Add break times within working hours
- [ ] Add different hours for different services
- [ ] Add calendar view for working hours
- [ ] Add bulk edit for multiple days at once
- [ ] Add working hours history/changelog
