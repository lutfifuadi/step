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
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm px-0" style="position: sticky; top: 0; z-index: 1030;">
    <div class="container">
      <a href="{{ route('pages-home') }}" class="navbar-brand d-flex align-items-center gap-2 text-decoration-none">
        <span class="fw-bold fs-5" style="color: var(--teal-deep);">STEP</span>
        <span class="d-none d-md-inline text-muted small">Paternal Involvement</span>
      </a>

      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#stepNavbar" aria-controls="stepNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="stepNavbar">
        <div class="navbar-nav ms-auto gap-2 align-items-center">
          <span class="navbar-text me-2">Halo, <strong>{{ auth()->user()->name }}</strong></span>
          <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold px-3" style="border-radius:20px;">
              Keluar
            </button>
          </form>
        </div>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-3 col-lg-2 d-md-block bg-white sidebar border-end min-vh-100 py-4 px-3 shadow-sm">
        <div class="position-sticky">
          <ul class="nav flex-column gap-2">
            @if(auth()->user()->hasRole('admin'))
              <li class="nav-item">
                <a class="nav-link rounded p-2 fw-semibold d-flex align-items-center gap-2 {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.dashboard') }}">
                  <i class="icon-base ti tabler-smart-home"></i> Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link rounded p-2 fw-semibold d-flex align-items-center gap-2 {{ request()->routeIs('admin.expressions.*') ? 'bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.expressions.index') }}">
                  <i class="icon-base ti tabler-message"></i> Moderasi Ekspresi
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link rounded p-2 fw-semibold d-flex align-items-center gap-2 {{ request()->routeIs('admin.konselor.*') ? 'bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.konselor.index') }}">
                  <i class="icon-base ti tabler-phone-call"></i> Kontak Konselor
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link rounded p-2 fw-semibold d-flex align-items-center gap-2 {{ request()->routeIs('admin.program-contents.*') ? 'bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.program-contents.index') }}">
                  <i class="icon-base ti tabler-layout-dashboard"></i> Konten Landing Page
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link rounded p-2 fw-semibold d-flex align-items-center gap-2 {{ request()->routeIs('admin.audit-log.*') ? 'bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.audit-log.index') }}">
                  <i class="icon-base ti tabler-history"></i> Audit Log
                </a>
              </li>
            @endif

            @if(auth()->user()->hasRole('researcher') || auth()->user()->hasRole('admin'))
              <li class="nav-item border-top pt-2 mt-2">
                <span class="text-uppercase text-muted fw-bold px-2" style="font-size: 0.75rem;">Peneliti</span>
              </li>
              <li class="nav-item">
                <a class="nav-link rounded p-2 fw-semibold d-flex align-items-center gap-2 {{ request()->routeIs('researcher.dashboard') ? 'bg-primary text-white' : 'text-dark' }}" href="{{ route('researcher.dashboard') }}">
                  <i class="icon-base ti tabler-search"></i> Riset & Ekspor
                </a>
              </li>
            @endif
          </ul>
        </div>
      </nav>

      <!-- Main Content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 min-vh-100">
        @yield('content')
      </main>
    </div>
  </div>

  @vite([
    'resources/js/app.js'
  ])

  @yield('page-script')
</body>
</html>
