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
<body>
  <!-- Header / Navbar -->
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
        <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
          <li class="nav-item">
            <a href="{{ route('pages-home') }}" class="nav-link fw-semibold px-3 py-2" style="{{ request()->routeIs('pages-home') ? 'background-color: var(--teal-deep); color: white !important; border-radius: 5px;' : 'border-radius: 5px;' }}">Beranda</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('tentang') }}" class="nav-link fw-semibold px-3 py-2" style="{{ request()->routeIs('tentang') ? 'background-color: var(--teal-deep); color: white !important; border-radius: 5px;' : 'border-radius: 5px;' }}">Tentang</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('edukasi') }}" class="nav-link fw-semibold px-3 py-2" style="{{ request()->routeIs('edukasi') ? 'background-color: var(--teal-deep); color: white !important; border-radius: 5px;' : 'border-radius: 5px;' }}">Edukasi</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('pencegahan') }}" class="nav-link fw-semibold px-3 py-2" style="{{ request()->routeIs('pencegahan') ? 'background-color: var(--teal-deep); color: white !important; border-radius: 5px;' : 'border-radius: 5px;' }}">Pencegahan</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('ekspresi.create') }}" class="nav-link fw-semibold px-3 py-2" style="{{ request()->routeIs('ekspresi.*') ? 'background-color: var(--teal-deep); color: white !important; border-radius: 5px;' : 'border-radius: 5px;' }}">Ruang Ekspresi</a>
          </li>
        </ul>

        <div class="d-flex align-items-center gap-2">
          @auth
            @if(auth()->user()->hasRole('admin'))
              <a href="{{ route('admin.dashboard') }}" class="btn btn-sm fw-semibold px-4" style="background-color: var(--teal-deep); color:#fff; border-radius:5px;">
                Dashboard Admin
              </a>
            @elseif(auth()->user()->hasRole('researcher'))
              <a href="{{ route('researcher.dashboard') }}" class="btn btn-sm fw-semibold px-4" style="background-color: var(--teal-deep); color:#fff; border-radius:5px;">
                Dashboard Peneliti
              </a>
            @else
              <a href="{{ route('dashboard') }}" class="btn btn-sm fw-semibold px-4" style="background-color: var(--teal-deep); color:#fff; border-radius:5px;">
                Dashboard
              </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="m-0">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold px-3" style="border-radius:5px;">
                Keluar
              </button>
            </form>
          @else
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary fw-semibold px-4" style="border-radius:5px;">
              Login
            </a>
            <a href="{{ route('register') }}" class="btn btn-sm fw-semibold px-4 text-white" style="background-color: var(--teal-deep); border-radius:5px;">
              Daftar
            </a>
          @endauth
        </div>
      </div>
    </div>
  </nav>

  <!-- Content -->
  <main class="py-0">
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="bg-dark text-white py-5 mt-auto">
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-6">
          <h5 class="fw-bold text-white mb-3">STEP (Paternal Involvement)</h5>
          <p class="text-white-50 mb-0">STEP adalah ruang aman bagi remaja untuk mengekspresikan harapan, cerita, dan aspirasi mengenai peran ayah demi masa depan pengasuhan yang lebih baik.</p>
        </div>
        <div class="col-lg-3 col-md-6">
          <h6 class="fw-bold text-white mb-3">Tautan Langsung</h6>
          <ul class="list-unstyled mb-0">
            <li class="mb-2"><a href="{{ route('pages-home') }}" class="text-white-50 text-decoration-none hover-white">Beranda</a></li>
            <li class="mb-2"><a href="{{ route('tentang') }}" class="text-white-50 text-decoration-none hover-white">Tentang Kami</a></li>
            <li class="mb-2"><a href="{{ route('edukasi') }}" class="text-white-50 text-decoration-none hover-white">Edukasi</a></li>
            <li class="mb-2"><a href="{{ route('pencegahan') }}" class="text-white-50 text-decoration-none hover-white">Pencegahan</a></li>
          </ul>
        </div>
        <div class="col-lg-3 col-md-6">
          <h6 class="fw-bold text-white mb-3">Kontak & Bantuan</h6>
          <p class="text-white-50 small mb-2">Butuh teman bercerita atau bantuan mendesak?</p>
          <p class="text-white-50 small mb-0">Hubungi BK MAN 1 Kota Bandung atau konselor sekolah terdekat.</p>
        </div>
      </div>
      <hr class="border-secondary my-4">
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start">
          <span class="text-white-50 small">&copy; {{ date('Y') }} STEP. Dilindungi Undang-Undang.</span>
        </div>
        <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
          <a href="{{ route('terms.show') }}" class="text-white-50 text-decoration-none small me-3 hover-white">Syarat & Ketentuan</a>
          <a href="{{ route('policy.show') }}" class="text-white-50 text-decoration-none small hover-white">Kebijakan Privasi</a>
        </div>
      </div>
    </div>
  </footer>

  @vite([
    'resources/js/app.js'
  ])

  @yield('page-script')
</body>
</html>
