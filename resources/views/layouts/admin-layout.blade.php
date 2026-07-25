@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
  $configData = Helper::appClasses();
  $user = auth()->user();
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
  <title>@hasSection('title')@yield('title') | @endif{{ config('variables.templateName') }} - {{ config('variables.templateSuffix') }}</title>
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

  <div x-data="{ mobileSidebarOpen: false, userDropdownOpen: false }" class="step-dashboard-wrapper">
    <!-- Sidebar Overlay for mobile screen -->
    <div 
      class="step-sidebar-overlay" 
      :class="{ 'show active d-block': mobileSidebarOpen }" 
      @click="mobileSidebarOpen = false"
      style="display: none;">
    </div>

    <!-- Sidebar -->
    <aside 
      class="step-sidebar" 
      :class="{ 'show active': mobileSidebarOpen }">
      
      <!-- Brand Logo / STEP -->
      <div class="step-sidebar__brand">
        <a href="{{ route('pages-home') }}" class="step-sidebar__brand-logo d-flex align-items-center gap-2">
          <span>STEP</span>
        </a>
        <span class="step-sidebar__brand-text">Paternal Involvement</span>
      </div>

      <!-- Menu Items -->
      <div class="flex-grow-1 overflow-y-auto py-3">
        <nav class="d-flex flex-column gap-1">
          @if($user && $user->hasRole('admin'))
            <!-- Header Section ADMIN -->
            <div class="step-sidebar__section-header">ADMINISTRATOR</div>

            <a class="step-sidebar__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
              <i class="icon-base ti tabler-smart-home"></i>
              <span>Dashboard Admin</span>
            </a>
            
            <a class="step-sidebar__item {{ request()->routeIs('admin.expressions.*') ? 'active' : '' }}" href="{{ route('admin.expressions.index') }}">
              <i class="icon-base ti tabler-message-circle"></i>
              <span>Moderasi Ekspresi</span>
            </a>

            <a class="step-sidebar__item {{ request()->routeIs('admin.konselor.*') ? 'active' : '' }}" href="{{ route('admin.konselor.index') }}">
              <i class="icon-base ti tabler-phone-call"></i>
              <span>Kontak Konselor</span>
            </a>

            <a class="step-sidebar__item {{ request()->routeIs('admin.program-contents.*') ? 'active' : '' }}" href="{{ route('admin.program-contents.index') }}">
              <i class="icon-base ti tabler-layout-dashboard"></i>
              <span>Konten Landing Page</span>
            </a>

            <a class="step-sidebar__item {{ request()->routeIs('admin.audit-log.*') ? 'active' : '' }}" href="{{ route('admin.audit-log.index') }}">
              <i class="icon-base ti tabler-history"></i>
              <span>Audit Log</span>
            </a>

            <!-- Divider Line & Header Section PENELITI -->
            <div class="border-top border-white-10 my-2 mx-3"></div>
            <div class="step-sidebar__section-header">PENELITI</div>

            <a class="step-sidebar__item {{ request()->routeIs('researcher.dashboard') ? 'active' : '' }}" href="{{ route('researcher.dashboard') }}">
              <i class="icon-base ti tabler-search"></i>
              <span>Dashboard Peneliti</span>
            </a>

            <a class="step-sidebar__item {{ request()->routeIs('researcher.export.*') ? 'active' : '' }}" href="{{ route('researcher.dashboard') }}">
              <i class="icon-base ti tabler-download"></i>
              <span>Ekspor Data</span>
            </a>
          @elseif($user && $user->hasRole('researcher'))
            <!-- Header Section PENELITI ONLY -->
            <div class="step-sidebar__section-header">PENELITI</div>

            <a class="step-sidebar__item {{ request()->routeIs('researcher.dashboard') ? 'active' : '' }}" href="{{ route('researcher.dashboard') }}">
              <i class="icon-base ti tabler-search"></i>
              <span>Dashboard Peneliti</span>
            </a>

            <a class="step-sidebar__item {{ request()->routeIs('researcher.export.*') ? 'active' : '' }}" href="{{ route('researcher.dashboard') }}">
              <i class="icon-base ti tabler-download"></i>
              <span>Ekspor Data</span>
            </a>
          @endif
        </nav>
      </div>

      <!-- Footer Sidebar / Sign Out -->
      <div class="step-sidebar__footer">
        <form method="POST" action="{{ route('logout') }}" class="m-0">
          @csrf
          <button type="submit" class="btn btn-sm btn-outline-danger w-100 fw-semibold d-flex align-items-center justify-content-center gap-2" style="border-radius:20px;">
            <i class="icon-base ti tabler-logout"></i>
            <span>Keluar</span>
          </button>
        </form>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="step-main-content">
      
      <!-- Topbar / Header -->
      <header class="step-topnav">
        
        <!-- Left Side: Mobile Menu Hamburger & Dynamic Breadcrumb -->
        <div class="d-flex align-items-center gap-3">
          <!-- Mobile Hamburger Toggle -->
          <button 
            type="button" 
            class="btn p-1 d-lg-none text-dark" 
            @click="mobileSidebarOpen = true"
            aria-label="Buka Sidebar">
            <i class="icon-base ti tabler-menu-2 fs-3"></i>
          </button>

          <!-- Breadcrumb Dynamic -->
          @php
            $breadcrumbsList = [];
            // Buat breadcrumbs dinamis berdasarkan request route
            if (request()->routeIs('admin.*')) {
                $breadcrumbsList[] = ['name' => 'Admin', 'url' => route('admin.dashboard')];
                if (request()->routeIs('admin.dashboard')) {
                    $breadcrumbsList[] = ['name' => 'Dashboard', 'url' => null];
                } elseif (request()->routeIs('admin.expressions.*')) {
                    $breadcrumbsList[] = ['name' => 'Moderasi Ekspresi', 'url' => null];
                } elseif (request()->routeIs('admin.konselor.*')) {
                    $breadcrumbsList[] = ['name' => 'Kontak Konselor', 'url' => null];
                } elseif (request()->routeIs('admin.program-contents.*')) {
                    $breadcrumbsList[] = ['name' => 'Konten Landing Page', 'url' => null];
                } elseif (request()->routeIs('admin.audit-log.*')) {
                    $breadcrumbsList[] = ['name' => 'Audit Log', 'url' => null];
                }
            } elseif (request()->routeIs('researcher.*')) {
                $breadcrumbsList[] = ['name' => 'Peneliti', 'url' => route('researcher.dashboard')];
                if (request()->routeIs('researcher.dashboard')) {
                    $breadcrumbsList[] = ['name' => 'Dashboard & Ekspor', 'url' => null];
                }
            }
          @endphp
          
          <nav aria-label="breadcrumb">
            <ul class="step-breadcrumb">
              <li>
                <a href="{{ route('pages-home') }}" class="d-flex align-items-center gap-1">
                  <i class="icon-base ti tabler-home"></i>
                  <span>Home</span>
                </a>
              </li>
              @foreach($breadcrumbsList as $crumb)
                <li>
                  @if($crumb['url'])
                    <a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
                  @else
                    <span>{{ $crumb['name'] }}</span>
                  @endif
                </li>
              @endforeach
            </ul>
          </nav>
        </div>

        <!-- Right Side: User Menu / Profile Dropdown -->
        <div class="d-flex align-items-center gap-3">
          <!-- Role Badge -->
          @if($user)
            @if($user->hasRole('admin'))
              <span class="step-role-badge step-role-badge--admin">Admin</span>
            @elseif($user->hasRole('researcher'))
              <span class="step-role-badge step-role-badge--researcher">Peneliti</span>
            @endif
          @endif

          <!-- Dropdown User -->
          <div class="position-relative" @click.away="userDropdownOpen = false">
            <button 
              type="button" 
              class="border-0 bg-transparent p-0 d-flex align-items-center gap-2 focus:outline-none"
              @click="userDropdownOpen = !userDropdownOpen"
              aria-expanded="false"
              aria-haspopup="true">
              <!-- Avatar -->
              <div class="step-avatar">
                @if($user && $user->name)
                  {{ strtoupper(substr($user->name, 0, 2)) }}
                @else
                  U
                @endif
              </div>
              <span class="d-none d-md-inline font-medium text-dark small">{{ $user ? $user->name : 'User' }}</span>
              <i class="icon-base ti tabler-chevron-down text-muted small transition-transform duration-200" :class="{ 'rotate-180': userDropdownOpen }"></i>
            </button>

            <!-- Dropdown Menu -->
            <div 
              class="step-dropdown-menu position-absolute end-0" 
              x-show="userDropdownOpen"
              x-transition:enter="transition ease-out duration-100"
              x-transition:enter-start="transform opacity-0 scale-95"
              x-transition:enter-end="transform opacity-100 scale-100"
              x-transition:leave="transition ease-in duration-75"
              x-transition:leave-start="transform opacity-100 scale-100"
              x-transition:leave-end="transform opacity-0 scale-95"
              style="display: none; z-index: 1050;">
              
              <div class="px-4 py-2 border-bottom">
                <span class="d-block font-semibold text-dark text-truncate" style="max-width: 180px;">{{ $user ? $user->name : 'User' }}</span>
                <span class="d-block text-muted small text-truncate" style="max-width: 180px;">{{ $user ? $user->email : '' }}</span>
              </div>

              <a href="{{ route('pages-home') }}" class="step-dropdown-menu__item">
                <i class="icon-base ti tabler-home"></i>
                <span>Lihat Beranda</span>
              </a>

              @if($user && $user->hasRole('admin'))
                <a href="{{ route('admin.dashboard') }}" class="step-dropdown-menu__item">
                  <i class="icon-base ti tabler-dashboard"></i>
                  <span>Dashboard Admin</span>
                </a>
              @endif

              @if($user && ($user->hasRole('researcher') || $user->hasRole('admin')))
                <a href="{{ route('researcher.dashboard') }}" class="step-dropdown-menu__item">
                  <i class="icon-base ti tabler-search"></i>
                  <span>Dashboard Peneliti</span>
                </a>
              @endif

              <div class="dropdown-divider my-1"></div>

              <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="step-dropdown-menu__item w-100 text-start border-0 bg-transparent text-danger">
                  <i class="icon-base ti tabler-logout text-danger"></i>
                  <span>Keluar</span>
                </button>
              </form>
            </div>
          </div>
        </div>

      </header>

      <!-- Page Content Content -->
      <main class="flex-grow-1 p-4" style="background-color: var(--cream);">
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