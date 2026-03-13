<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ACL Master Config (Fixed Permissions / Dynamic Roles)
    |--------------------------------------------------------------------------
    |
    | - هذا الملف هو "مصدر الحقيقة" لتعريف الموارد (resources) وأفعالها (actions)
    |   وتسميات واجهتها (labels) بلغات متعددة.
    | - تُستخدم هذه البيانات في:
    |   1) Seeder: لمزامنة أذونات Spatie (permissions) إلى قاعدة البيانات.
    |   2) الواجهة (Vue/Inertia): لبناء جدول الاختيار وعناوين الموارد/الأفعال.
    | - الأدوار Roles ديناميكية في DB مع display_name مترجم (spatie/laravel-translatable).
    |
    */

    // اللغات المدعومة (اختياري للتوحيد في الواجهة)
    'locales' => ['en', 'ar'],

    // الحارس الافتراضي
    'guard' => 'admin',

    // أفعال افتراضية يمكن استخدامها مستقبلًا لتقليل التكرار (غير مستخدمة الآن)
    'default_actions' => ['view', 'create', 'update', 'delete'],

    /*
    |--------------------------------------------------------------------------
    | Resources & Actions (ثابتة عبر الكود)
    |--------------------------------------------------------------------------
    | المفتاح = اسم المورد (kebab-case مفضل)
    | القيمة = مصفوفة الأفعال المسموحة لهذا المورد
    */
    'resources' => [
    // Dashboard
    'dashboard'    => ['view'],

    // Location Management
    'areas'        => ['view', 'create', 'update', 'delete'],
    'districts'    => ['view', 'create', 'update', 'delete'],
    'governorates' => ['view', 'create', 'update', 'delete'],

    // User Management
    'users'        => ['view', 'create', 'update', 'delete'],
    'admins'       => ['view', 'create', 'update', 'delete'],
    'roles'        => ['view', 'create', 'update', 'delete'],
    'permissions'  => ['view'],
    'profile'      => ['view', 'update'],
    'activitylogs' => ['view'],

    // KYC & Verification
    'kycs'         => ['view', 'create', 'update', 'delete'],

    // Content Management
    'tags'         => ['view', 'create', 'update', 'delete'],
    'categories'   => ['view', 'create', 'update', 'delete'],
    'landing-page-sections' => ['view', 'create', 'update', 'delete'],
    'contact-messages' => ['view', 'delete'],
    'terms-and-conditions' => ['view', 'create', 'update', 'delete'],
    'discount-codes' => ['view', 'create', 'update', 'delete'],

    // Address Management
    'addresses'    => ['view', 'create', 'update', 'delete'],

    // Chef Management
    'chefs'        => ['view', 'create', 'update', 'delete'],
    'chef-services' => ['view', 'create', 'update', 'delete'],
    'chef-service-equipment' => ['view', 'create', 'update', 'delete'],
    'chef-service-tags' => ['view', 'create', 'update', 'delete'],
    'chef-service-images' => ['view', 'create', 'update', 'delete'],
    'chef-categories' => ['view', 'create', 'update', 'delete'],
    'chef-gallery' => ['view', 'create', 'update', 'delete'],
    'chef-working-hours' => ['view', 'create', 'update', 'delete'],
    'chef-vacations' => ['view', 'create', 'update', 'delete'],

    // Booking Management
    'bookings'     => ['view', 'create', 'update', 'delete'],
    'booking-transactions' => ['view', 'create', 'update', 'delete'],

    // Rating & Reviews
    'chef-service-ratings' => ['view', 'create', 'update', 'delete'],

    // Financial Management
    'chef-wallets' => ['view', 'create', 'update', 'delete'],
    'chef-wallet-transactions' => ['view', 'create', 'update', 'delete'],
    'chef-withdrawal-requests' => ['view', 'create', 'update', 'delete'],
    'withdrawal-methods' => ['view', 'create', 'update', 'delete'],

    // Reports
    'reports'      => ['view'],
    'financial-reports' => ['view'],
    'booking-reports' => ['view'],
    'chef-reports' => ['view'],

    // Settings
    'settings'     => ['view', 'update'],
    'system-settings' => ['view', 'update'],

    ],

    /*
    |--------------------------------------------------------------------------
    | Resource Labels (واجهة المستخدم)
    |--------------------------------------------------------------------------
    | ترجمة أسماء الموارد للعرض فقط (لا تُخزن في DB).
    */
    'resource_labels' => [
    // Dashboard
    'dashboard'    => ['en' => 'Dashboard',          'ar' => 'لوحة التحكم'],

    // Location Management
    'areas'        => ['en' => 'Neighborhoods',     'ar' => 'الأحياء'],
    'districts'    => ['en' => 'Cities',     'ar' => 'المدن'],
    'governorates' => ['en' => 'Regions',  'ar' => 'المناطق'],

    // User Management
    'users'        => ['en' => 'Users',         'ar' => 'المستخدمون'],
    'admins'       => ['en' => 'Admins',        'ar' => 'المشرفون'],
    'roles'        => ['en' => 'Roles',         'ar' => 'الأدوار'],
    'permissions'  => ['en' => 'Permissions',   'ar' => 'الصلاحيات'],
    'profile'      => ['en' => 'Profile',       'ar' => 'الملف الشخصي'],
    'activitylogs' => ['en' => 'Activity Logs', 'ar' => 'سجل التغييرات'],

    // KYC & Verification
    'kycs'         => ['en' => 'KYC Requests',  'ar' => 'طلبات KYC'],

    // Content Management
    'addresses'    => ['en' => 'Addresses',     'ar' => 'العناوين'],
    'tags'         => ['en' => 'Tags',          'ar' => 'الوسوم'],
    'categories'   => ['en' => 'Categories',    'ar' => 'التصنيفات'],
    'landing-page-sections' => ['en' => 'Landing Pages', 'ar' => 'الصفحات الخارجية'],
    'contact-messages' => ['en' => 'Contact Messages', 'ar' => 'رسائل التواصل'],
    'terms-and-conditions' => ['en' => 'Terms and Conditions', 'ar' => 'الشروط والأحكام'],
    'discount-codes' => ['en' => 'Discount Codes', 'ar' => 'أكواد الخصم'],

    // Chef Management
    'chefs'        => ['en' => 'Chefs',         'ar' => 'الطهاة'],
    'chef-services' => ['en' => 'Chef Services', 'ar' => 'خدمات الطهاة'],
    'chef-service-equipment' => ['en' => 'Service Equipment', 'ar' => 'معدات الخدمات'],
    'chef-service-tags' => ['en' => 'Service Tags', 'ar' => 'وسوم الخدمات'],
    'chef-service-images' => ['en' => 'Service Images', 'ar' => 'صور الخدمات'],
    'chef-categories' => ['en' => 'Chef Categories', 'ar' => 'تصنيفات الطهاة'],
    'chef-gallery' => ['en' => 'Chef Gallery', 'ar' => 'معرض الطهاة'],
    'chef-working-hours' => ['en' => 'Working Hours', 'ar' => 'ساعات العمل'],
    'chef-vacations' => ['en' => 'Vacations', 'ar' => 'الإجازات'],

    // Booking Management
    'bookings'     => ['en' => 'Bookings',      'ar' => 'الحجوزات'],
    'booking-transactions' => ['en' => 'Booking Transactions', 'ar' => 'معاملات الحجوزات'],

    // Rating & Reviews
    'chef-service-ratings' => ['en' => 'Service Ratings', 'ar' => 'تقييمات الخدمات'],

    // Financial Management
    'chef-wallets' => ['en' => 'Chef Wallets', 'ar' => 'محافظ الطهاة'],
    'chef-wallet-transactions' => ['en' => 'Wallet Transactions', 'ar' => 'معاملات المحافظ'],
    'chef-withdrawal-requests' => ['en' => 'Withdrawal Requests', 'ar' => 'طلبات السحب'],
    'withdrawal-methods' => ['en' => 'Withdrawal Methods', 'ar' => 'طرق السحب'],

    // Reports
    'reports'      => ['en' => 'Reports',       'ar' => 'التقارير'],
    'financial-reports' => ['en' => 'Financial Reports', 'ar' => 'التقارير المالية'],
    'booking-reports' => ['en' => 'Booking Reports', 'ar' => 'تقارير الحجوزات'],
    'chef-reports' => ['en' => 'Chef Reports', 'ar' => 'تقارير الطهاة'],

    // Settings
    'settings'     => ['en' => 'Settings',      'ar' => 'الإعدادات'],
    'system-settings' => ['en' => 'System Settings', 'ar' => 'إعدادات النظام'],

    ],

    /*
    |--------------------------------------------------------------------------
    | Action Labels (واجهة المستخدم)
    |--------------------------------------------------------------------------
    | ترجمة أسماء الأفعال للعرض فقط (لا تُخزن في DB).
    */
    'action_labels' => [
        'view'   => ['en' => 'View',   'ar' => 'عرض'],
        'create' => ['en' => 'Create', 'ar' => 'إنشاء'],
        'update' => ['en' => 'Update', 'ar' => 'تعديل'],
        'delete' => ['en' => 'Delete', 'ar' => 'حذف'],
    ],

];
