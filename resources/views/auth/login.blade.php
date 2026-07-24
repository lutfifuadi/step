@php
use Illuminate\Support\Facades\Route;
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Login')
@section('meta_description', 'Masuk ke akun STEP untuk melanjutkan perjalanan keterlibatan ayah dalam mendukung tumbuh kembang anak. Platform edukasi dan dukungan paternal involvement.')
@section('meta_keywords', 'login STEP, masuk akun, paternal involvement, keterlibatan ayah, edukasi ayah')
@section('og_title', 'Login STEP - Paternal Involvement')
@section('og_description', 'Masuk ke akun STEP untuk melanjutkan perjalanan keterlibatan ayah dalam mendukung tumbuh kembang anak.')
@section('og_type', 'website')
@section('robots', 'noindex, nofollow')

@section('page-style')
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
<style>
  /* Custom style overrides for STEP Theme */
  :root, html, body, .authentication-wrapper, .authentication-wrapper * {
    font-family: 'Poppins', sans-serif !important;
  }

  html, body {
    height: 100vh !important;
    height: 100dvh !important;
    overflow: hidden !important;
    margin: 0 !important;
    padding: 0 !important;
  }

  .authentication-wrapper {
    height: 100vh !important;
    height: 100dvh !important;
    overflow: hidden !important;
  }

  .authentication-inner {
    height: 100vh !important;
    height: 100dvh !important;
    margin: 0 !important;
    overflow: hidden !important;
  }

  .auth-cover-bg {
    height: 100vh !important;
    height: 100dvh !important;
    overflow: hidden !important;
  }

  .authentication-bg {
    height: 100vh !important;
    height: 100dvh !important;
    overflow: hidden !important;
  }

  .authentication-bg {
    background-color: #fdf6ee !important; /* Cream background */
  }
  
  .form-control,
  .input-group,
  .input-group-text,
  .btn,
  .alert,
  .form-check-input,
  .w-px-400 {
    border-radius: 5px !important;
  }

  .btn-primary {
    background-color: #003d33 !important;
    border-color: #003d33 !important;
    color: #ffffff !important;
    font-weight: 600;
    transition: all 0.3s ease-in-out !important;
  }
  
  .btn-primary:hover,
  .btn-primary:focus,
  .btn-primary:active {
    background-color: #005b4e !important;
    border-color: #005b4e !important;
    box-shadow: 0 4px 12px rgba(0, 61, 51, 0.25) !important;
    transform: translateY(-1px);
  }

  /* Target text links specifically, not icon buttons */
  .authentication-bg a:not(.btn) {
    color: #003d33 !important;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.2s ease-in-out;
  }
  
  .authentication-bg a:not(.btn):hover {
    color: #00695c !important;
    text-decoration: underline !important;
  }

  .form-check-input:checked {
    background-color: #003d33 !important;
    border-color: #003d33 !important;
  }

  .auth-cover-bg {
    background: linear-gradient(135deg, #003d33 0%, #005b4e 100%) !important;
    position: relative;
    width: 100%;
    height: 100vh;
  }

  .auth-illustration {
    max-height: 55vh !important;
    object-fit: contain;
    z-index: 2;
    margin-bottom: 8rem !important; /* space for absolute positioned content */
  }

  .platform-bg {
    opacity: 0.15;
    z-index: 1;
  }

  /* Soft border on focus for fields */
  .form-control:focus, .input-group-text:focus {
    border-color: #003d33 !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 61, 51, 0.1) !important;
  }

  /* Brand Logo Responsive Colors */
  .auth-cover-brand .app-brand-text-title {
    color: #ffffff !important;
  }
  .auth-cover-brand .app-brand-subtext {
    color: rgba(255, 255, 255, 0.7) !important;
    border-left: 1px solid rgba(255, 255, 255, 0.3);
  }
  
  @media (max-width: 1199.98px) {
    .auth-cover-brand .app-brand-text-title {
      color: #003d33 !important;
    }
    .auth-cover-brand .app-brand-subtext {
      color: #6b7280 !important;
      border-left: 1px solid #ccc;
    }
  }

  /* Compact layout for mobile viewports to prevent overflow */
  @media (max-width: 575.98px) {
    .w-px-400 {
      width: 100% !important;
      max-width: 100% !important;
    }
    .authentication-bg {
      padding: 1.5rem !important;
    }
    .my-8 {
      margin-top: 1rem !important;
      margin-bottom: 1rem !important;
    }
    .mb-6 {
      margin-bottom: 0.75rem !important;
    }
    .my-6 {
      margin-top: 0.75rem !important;
      margin-bottom: 0.75rem !important;
    }
    .auth-cover-brand {
      top: 1rem !important;
      left: 1rem !important;
    }
  }
</style>
@endsection

@section('content')
<div class="authentication-wrapper authentication-cover">
  <!-- Logo -->
  <a href="{{ url('/') }}" class="app-brand auth-cover-brand d-flex align-items-center gap-2 text-decoration-none">
    <span class="app-brand-text demo fw-bold app-brand-text-title" style="font-size: 1.5rem;">STEP</span>
    <span class="app-brand-subtext ps-2 small">Paternal Involvement</span>
  </a>
  <!-- /Logo -->
  <div class="authentication-inner row m-0">
    <!-- /Left Text -->
    <div class="d-none d-xl-flex col-xl-8 p-0">
      <div class="auth-cover-bg d-flex flex-column justify-content-center align-items-center text-white px-5 position-relative w-100 h-100">
        <!-- Overlay pattern to make it more estetik -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: url('{{ asset('assets/img/illustrations/bg-shape-image-' . $configData['theme'] . '.png') }}'); background-size: cover; opacity: 0.1; z-index: 1;"></div>
        
        <div class="z-2 text-center max-w-lg mb-4" style="max-width: 600px; padding: 3rem; background: rgba(0, 61, 51, 0.4); border-radius: 1rem; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
          <h1 class="text-white fw-bold mb-3" style="font-size: 3rem; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">Aplikasi STEP</h1>
          <p class="fs-5 fw-light m-0" style="color: #e0e0e0; line-height: 1.6;">Paternal Involvement: Membangun peran dan keterlibatan aktif ayah demi tumbuh kembang anak yang optimal.</p>
        </div>
      </div>
    </div>
    <!-- /Left Text -->

    <!-- Login -->
    <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6 overflow-hidden">
      <div class="w-px-400 mx-auto mt-0 pt-0 d-flex flex-column justify-content-center h-100">
        <h4 class="mb-1" style="color: #003d33; font-weight: 700;">Selamat Datang di STEP! 👋</h4>
        <p class="mb-6 text-muted">Silakan masuk ke akun Anda dan mulai perjalanan keterlibatan ayah.</p>

        @if (session('status'))
        <div class="alert alert-success mb-1" role="alert" style="border-radius: 5px;">
          <div class="alert-body">
            {{ session('status') }}
          </div>
        </div>
        @endif
        <form id="formAuthentication" class="mb-6" action="{{ route('login') }}" method="POST">
          @csrf
          <div class="mb-6">
            <label for="login-email" class="form-label" style="color: #003d33; font-weight: 500;">Email</label>
            <input type="text" class="form-control @error('email') is-invalid @enderror" id="login-email" name="email"
              placeholder="john@example.com" autofocus value="{{ old('email') }}" style="border-radius: 5px;" />
            @error('email')
            <span class="invalid-feedback" role="alert">
              <span class="fw-medium">{{ $message }}</span>
            </span>
            @enderror
          </div>
          <div class="mb-6 form-password-toggle">
            <label class="form-label" for="login-password" style="color: #003d33; font-weight: 500;">Password</label>
            <div class="input-group input-group-merge @error('password') is-invalid @enderror" style="border-radius: 5px;">
              <input type="password" id="login-password" class="form-control @error('password') is-invalid @enderror"
                name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="password" style="border-radius: 5px 0 0 5px;" />
              <span class="input-group-text cursor-pointer" style="border-radius: 0 5px 5px 0;"><i class="icon-base ti tabler-eye-off"></i></span>
            </div>
            @error('password')
            <span class="invalid-feedback" role="alert">
              <span class="fw-medium">{{ $message }}</span>
            </span>
            @enderror
          </div>
          <div class="my-8">
            <div class="d-flex justify-content-between align-items-center">
              <div class="form-check mb-0 ms-2">
                <input class="form-check-input" type="checkbox" id="remember-me" name="remember"
                  {{ old('remember') ? 'checked' : '' }} style="border-radius: 3px;" />
                <label class="form-check-label" for="remember-me" style="color: #003d33; font-size: 0.9rem;"> Ingat Saya </label>
              </div>
              @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" style="color: #003d33; font-weight: 500; text-decoration: none;">
                Lupa Password?
              </a>
              @endif
            </div>
          </div>
          <button class="btn btn-primary d-grid w-100" type="submit" style="border-radius: 5px;">Sign in</button>
        </form>

        <p class="text-center">
          <span class="text-muted">Baru di platform kami?</span>
          @if (Route::has('register'))
          <a href="{{ route('register') }}" style="color: #003d33; font-weight: 600; text-decoration: none;">
            <span>Daftar Akun Baru</span>
          </a>
          @endif
        </p>

        <div class="divider my-6">
          <div class="divider-text">or</div>
        </div>

        <div class="d-flex justify-content-center">
          <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-facebook me-1_5">
            <i class="icon-base ti tabler-brand-facebook-filled icon-20px"></i>
          </a>

          <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-twitter me-1_5">
            <i class="icon-base ti tabler-brand-twitter-filled icon-20px"></i>
          </a>

          <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-github me-1_5">
            <i class="icon-base ti tabler-brand-github-filled icon-20px"></i>
          </a>

          <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-google-plus">
            <i class="icon-base ti tabler-brand-google-filled icon-20px"></i>
          </a>
        </div>
      </div>
    </div>
    <!-- /Login -->
  </div>
</div>
@endsection