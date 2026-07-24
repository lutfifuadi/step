@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
  $configData = Helper::appClasses();
@endphp
<!DOCTYPE html>
<html lang="{{ session()->get('locale') ?? app()->getLocale() }}"
  class="layout-navbar-fixed layout-compact"
  dir="{{ $configData['textDirection'] }}"
  data-skin="default"
  data-assets-path="{{ asset('/assets') . '/' }}"
  data-base-url="{{ url('/') }}"
  data-framework="laravel"
  data-bs-theme="{{ $configData['theme'] }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>@yield('title') | STEP - Paternal Involvement</title>
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  @vite([
    'resources/assets/vendor/fonts/iconify/iconify.css',
    'resources/assets/vendor/libs/node-waves/node-waves.scss',
    'resources/assets/vendor/scss/core.scss',
    'resources/assets/css/demo.css',
    'resources/css/app.css'
  ])

  @yield('page-style')
</head>
<body class="bg-light">
  <!-- Content -->
  <main class="min-vh-100 d-flex flex-column justify-content-center">
    @yield('content')
  </main>

  @vite([
    'resources/js/app.js'
  ])

  @yield('page-script')
</body>
</html>
