/**
 * Single source of truth for all landing page UI strings.
 * Both Arabic and English use the same components; only these labels change by locale.
 */
export const landingLabels = {
  ar: {
    pageTitle: 'مون شيف',
    nav: {
      home: 'الرئيسية',
      features: 'المميزات',
      howItWorks: 'كيف يعمل',
      topChefs: 'أفضل الطهاة',
      categories: 'التصنيفات',
      about: 'من نحن',
      whyUs: 'لماذا نحن',
      contact: 'تواصل معنا',
    },
    langSwitch: 'EN',
    footer: {
      tagline: 'منصة تربط الطهاة المحترفين بالعملاء لتقديم تجربة طهي استثنائية في منزلك',
      quickLinks: 'روابط سريعة',
      contactUs: 'تواصل معنا',
      followUs: 'تابعنا',
      copyright: 'مون شيف - جميع الحقوق محفوظة',
    },
    empty: {
      topChefs: 'سيتم عرض أفضل الطهاة قريباً',
      categories: 'سيتم عرض التصنيفات قريباً',
      testimonials: 'سيتم عرض آراء العملاء قريباً',
      partners: 'سيتم عرض الشركاء قريباً',
    },
    contact: {
      email: 'البريد الإلكتروني',
      phone: 'الهاتف',
      address: 'العنوان',
      workingHours: 'ساعات العمل',
      sendMessage: 'إرسال الرسالة',
      sendMessageLoading: 'جاري الإرسال...',
      sendUsMessage: 'أرسل لنا رسالة',
      name: 'الاسم',
      message: 'الرسالة',
      namePlaceholder: 'أدخل اسمك',
      emailPlaceholder: 'أدخل بريدك الإلكتروني',
      phonePlaceholder: 'أدخل رقم هاتفك',
      messagePlaceholder: 'اكتب رسالتك هنا...',
      success: 'تم إرسال رسالتك بنجاح!',
      error: 'حدث خطأ، حاول مرة أخرى',
      errorConnection: 'حدث خطأ في الاتصال، حاول مرة أخرى',
    },
    testimonialsPill: 'آراء العملاء',
    partnersPill: 'شركاؤنا',
    coreValues: 'قيمنا الأساسية',
    hero: {
      ctaDiscover: 'اكتشف الطهاة',
      ctaHowItWorks: 'كيف يعمل',
    },
  },
  en: {
    pageTitle: 'Mon Chef',
    nav: {
      home: 'Home',
      features: 'Features',
      howItWorks: 'How It Works',
      topChefs: 'Top Chefs',
      categories: 'Categories',
      about: 'About',
      whyUs: 'Why Us',
      contact: 'Contact',
    },
    langSwitch: 'عربي',
    footer: {
      tagline: 'A platform connecting professional chefs with customers for an exceptional cooking experience at your home',
      quickLinks: 'Quick Links',
      contactUs: 'Contact Us',
      followUs: 'Follow Us',
      copyright: 'Mon Chef - All Rights Reserved',
    },
    empty: {
      topChefs: 'Top chefs will be displayed soon',
      categories: 'Categories will be displayed soon',
      testimonials: 'Customer reviews will be displayed soon',
      partners: 'Partners will be displayed soon',
    },
    contact: {
      email: 'Email',
      phone: 'Phone',
      address: 'Address',
      workingHours: 'Working Hours',
      sendMessage: 'Send Message',
      sendMessageLoading: 'Sending...',
      sendUsMessage: 'Send us a message',
      name: 'Name',
      message: 'Message',
      namePlaceholder: 'Enter your name',
      emailPlaceholder: 'Enter your email',
      phonePlaceholder: 'Enter your phone number',
      messagePlaceholder: 'Write your message here...',
      success: 'Your message has been sent successfully!',
      error: 'An error occurred, please try again',
      errorConnection: 'Connection error, please try again',
    },
    testimonialsPill: 'Customer Reviews',
    partnersPill: 'Our Partners',
    coreValues: 'Our Core Values',
    hero: {
      ctaDiscover: 'Discover Chefs',
      ctaHowItWorks: 'How It Works',
    },
  },
}

/**
 * @param {'ar'|'en'} locale
 * @returns {typeof landingLabels.ar}
 */
export function getLandingLabels(locale) {
  return landingLabels[locale] || landingLabels.ar
}
