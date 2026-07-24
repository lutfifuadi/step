@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

@php
  $configData = Helper::appClasses();

  /* Display elements */
  $customizerHidden = $customizerHidden ?? '';
@endphp

{{-- Preload Poppins WOFF2 agar tidak ada FOUT --}}
@push('preload')
<link rel="preload" href="{{ asset('assets/fonts/Poppins-Regular.woff2') }}" as="font" type="font/woff2" crossorigin="anonymous">
<link rel="preload" href="{{ asset('assets/fonts/Poppins-Bold.woff2') }}" as="font" type="font/woff2" crossorigin="anonymous">
@endpush

@section('page-style')
<style>
  @font-face {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 400;
    src: url("{{ asset('assets/fonts/Poppins-Regular.woff2') }}") format('woff2');
    font-display: block;
  }

  @font-face {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 700;
    src: url("{{ asset('assets/fonts/Poppins-Bold.woff2') }}") format('woff2');
    font-display: block;
  }

  :root {
    --teal-deep:    #003d33;
    --teal-mid:     #00695c;
    --teal-soft:    #b2dfdb;
    --cream:        #fdf6ee;
    --amber:        #f59e0b;
    --amber-light:  #fef3c7;
    --sky:          #e0f7fa;
    --sky-dark:     #0277bd;
    --text-dark:    #1a1a2e;
    --text-muted:   #6b7280;
    --white:        #ffffff;
    --radius-xl:    1.5rem;
    --radius-pill:  100px;
    --shadow-card:  0 16px 40px rgba(0,61,51,.08);
  }

  body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--cream);
    color: var(--text-dark);
  }

  h1, h2, h3, h4, h5 {
    font-weight: 700;
    letter-spacing: -0.02em;
  }

  .section-py {
    padding-top: 5rem;
    padding-bottom: 5rem;
  }

  .section-py .container {
    max-width: 1320px;
  }

  .landing-hero {
    position: relative;
  }

  .landing-hero .text-center {
    margin-left: auto;
    margin-right: auto;
  }

  .hero-title {
    font-size: clamp(2.2rem, 5vw, 3.8rem);
    line-height: 1.1;
    color: var(--teal-deep);
    margin-bottom: 1.25rem;
  }

  .hero-subtitle {
    font-size: 1.05rem;
    color: rgba(26,26,46,.78);
    line-height: 1.75;
    max-width: 680px;
    margin: 0 auto 2rem;
  }

  .badge-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--amber-light);
    color: #92400e;
    font-size: .75rem;
    font-weight: 500;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: .35rem 1rem;
    border-radius: var(--radius-pill);
  }

  .btn-hero {
    display: inline-flex;
    align-items: center;
    gap: .6rem;
    background: var(--amber);
    color: var(--teal-deep);
    font-weight: 600;
    font-size: 1rem;
    padding: .95rem 2rem;
    border-radius: var(--radius-pill);
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: transform .25s ease, box-shadow .25s ease;
    box-shadow: 0 6px 24px rgba(245,158,11,.24);
  }

  .btn-hero:hover {
    transform: translateY(-1px);
    box-shadow: 0 12px 32px rgba(245,158,11,.28);
    color: var(--teal-deep);
  }

  .hero-card,
  .feature-card,
  .content-card,
  .info-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(0,0,0,.04);
    padding: 1.75rem;
  }

  .hero-card:hover,
  .feature-card:hover,
  .content-card:hover,
  .info-card:hover {
    transform: translateY(-2px);
  }

  .hero-card,
  .feature-card,
  .content-card,
  .info-card {
    transition: transform .3s ease, box-shadow .3s ease;
  }

  .feature-card h3,
  .content-card h4,
  .info-card h5 {
    margin-bottom: .75rem;
  }

  .feature-card p,
  .content-card p,
  .info-card p {
    color: var(--text-muted);
    line-height: 1.75;
  }
</style>
</style>
@endsection

@extends('layouts/commonMaster')

@section('layoutContent')
  <!-- Navbar: Start -->
  <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm px-0" style="position: sticky; top: 0; z-index: 1030;">
    <div class="container">
      <!-- Brand -->
      <a href="{{ route('pages-home') }}" class="navbar-brand d-flex align-items-center gap-2 text-decoration-none">
        <span class="fw-bold fs-5" style="color:#004d40;">STEP</span>
        <span class="d-none d-md-inline text-muted small">Paternal Involvement</span>
      </a>

      <!-- Mobile Toggle -->
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#stepNavbar" aria-controls="stepNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu -->
      <div class="collapse navbar-collapse" id="stepNavbar">
        <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
          <li class="nav-item">
            <a href="{{ route('pages-home') }}" class="nav-link fw-semibold px-3 py-2 rounded {{ request()->routeIs('pages-home') ? 'text-white' : 'text-dark' }}" style="{{ request()->routeIs('pages-home') ? 'background-color:#004d40;' : '' }}">Beranda</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('tentang') }}" class="nav-link fw-semibold px-3 py-2 rounded {{ request()->routeIs('tentang') ? 'text-white' : 'text-dark' }}" style="{{ request()->routeIs('tentang') ? 'background-color:#004d40;' : '' }}">Tentang</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('edukasi') }}" class="nav-link fw-semibold px-3 py-2 rounded {{ request()->routeIs('edukasi') ? 'text-white' : 'text-dark' }}" style="{{ request()->routeIs('edukasi') ? 'background-color:#004d40;' : '' }}">Edukasi</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('pencegahan') }}" class="nav-link fw-semibold px-3 py-2 rounded {{ request()->routeIs('pencegahan') ? 'text-white' : 'text-dark' }}" style="{{ request()->routeIs('pencegahan') ? 'background-color:#004d40;' : '' }}">Pencegahan</a>
          </li>
        </ul>

        <!-- CTA Button -->
        <a href="{{ route('ekspresi.create') }}" class="btn btn-sm fw-semibold px-4" style="background-color:#004d40; color:#fff; border-radius:20px;">
          Ruang Ekspresi
        </a>
      </div>
    </div>
  </nav>
  <!-- Navbar: End -->

  <!-- Content -->
  @yield('content')
  <!--/ Content -->
@endsection
