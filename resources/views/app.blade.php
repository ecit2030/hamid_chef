@php
  $locale = session('locale', config('app.locale', 'ar'));
  $dir = $locale === 'ar' ? 'rtl' : 'ltr';
  $langAttr = $locale === 'ar' ? 'ar' : 'en';
@endphp
<!DOCTYPE html>
<html lang="{{ $langAttr }}" dir="{{ $dir }}">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
        <title inertia>Mon Chef</title>
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        <link rel="apple-touch-icon" href="/favicon.svg">
        @routes
        @vite(['resources/js/app.js'])
        @inertiaHead
    </head>

<body class="font-sans antialiased">
  @inertia
</body>

</html>
