@php
$configData = Helper::appClasses();
$isFront = true;
@endphp

@extends('layouts/public-layout')

@section('title', 'Edukasi STEP')

@section('page-style')
@parent
<style>
  /* ─── CSS Variables & Base ─── */
  .badge-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--amber-light);
    color: #92400e;
    font-family: 'DM Sans', sans-serif;
    font-size: .75rem;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: .4rem 1.25rem;
    border-radius: var(--radius-pill);
    border: 1px solid rgba(245, 158, 11, 0.2);
  }

  .hero-title {
    font-size: clamp(2rem, 5vw, 3.2rem);
    line-height: 1.2;
    color: var(--white);
    margin-top: 1rem;
    margin-bottom: 1.25rem;
    font-weight: 700;
  }

  .hero-subtitle {
    font-size: clamp(1rem, 2vw, 1.15rem);
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.7;
    max-width: 650px;
    margin: 0 auto;
  }

  /* Bento-style education cards */
  .education-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    box-shadow: var(--shadow-card);
    transition: transform .3s ease, box-shadow .3s ease;
    border: 1px solid rgba(0, 61, 51, 0.05);
    position: relative;
    overflow: hidden;
  }

  @media (min-width: 768px) {
    .education-card {
      padding: 2.25rem;
    }
  }

  .education-card::before {
    content: '';
    position: absolute;
    width: 100px; height: 100px;
    border-radius: 50%;
    top: -20px; right: -20px;
    opacity: .04;
    background: var(--teal-deep);
  }

  .education-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 48px rgba(0,61,51,.12);
  }

  .education-card h3 {
    color: var(--teal-deep);
    font-weight: 700;
    font-size: 1.35rem;
    margin-bottom: 1rem;
    position: relative;
    padding-bottom: 0.5rem;
  }

  .education-card h3::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 30px;
    height: 3px;
    background-color: var(--amber);
    border-radius: 2px;
  }

  .education-card p {
    color: var(--text-muted);
    font-size: 0.92rem;
    line-height: 1.7;
    margin-bottom: 0;
  }

  /* Footer bento/content card */
  .content-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 1.75rem;
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(0, 61, 51, 0.05);
    position: relative;
    overflow: hidden;
  }

  @media (min-width: 768px) {
    .content-card {
      padding: 3rem;
    }
  }

  .content-card h4 {
    color: var(--teal-deep);
    font-weight: 700;
    font-size: 1.6rem;
  }

  .content-card p {
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.75;
    max-width: 720px;
  }

  .btn-hero {
    display: inline-flex; align-items: center; gap: .6rem;
    background: var(--amber);
    color: var(--teal-deep);
    font-family: 'DM Sans', sans-serif;
    font-weight: 600; font-size: 1rem;
    padding: .9rem 2rem;
    border-radius: var(--radius-pill);
    text-decoration: none;
    border: none; cursor: pointer;
    transition: all .25s ease;
    box-shadow: 0 4px 24px rgba(245,158,11,.35);
  }

  .btn-hero:hover {
    background: #fbbf24;
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(245,158,11,.45);
    color: var(--teal-deep);
  }
</style>
@endsection

@section('content')
<!-- Hero Header -->
<section class="subpage-header">
  <div class="subpage-grain"></div>
  <div class="container position-relative z-2">
    <span class="badge-pill">{!! isset($hero['hero']) && $hero['hero']->icon ? $hero['hero']->icon : 'Edukasi STEP' !!}</span>
    <h1 class="hero-title">{!! isset($hero['hero']) && $hero['hero']->title ? $hero['hero']->title : 'Edukasi Program STEP' !!}</h1>
    <p class="hero-subtitle">{!! isset($hero['hero']) && $hero['hero']->body ? $hero['hero']->body : 'Memahami pentingnya keterlibatan ayah bagi masa depan remaja.' !!}</p>
  </div>
</section>

<!-- Cards Section -->
<section class="py-4 py-md-5">
  <div class="container py-2 py-md-3">
    <div class="row g-3 g-md-4">
      @if($cards->count() > 0)
        @foreach($cards as $card)
          <div class="col-md-6 col-lg-4">
            <div class="education-card h-100">
              <h3>{!! $card->title !!}</h3>
              <p>{!! $card->body !!}</p>
            </div>
          </div>
        @endforeach
      @else
        <div class="col-md-6 col-lg-4">
          <div class="education-card h-100">
            <h3>Apa itu Keterlibatan Ayah?</h3>
            <p>Bukan sekadar mencari nafkah, tapi kehadiran secara emosional, mendidik, dan menjadi teman diskusi bagi remaja.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="education-card h-100">
            <h3>Dampak Positif</h3>
            <p>Remaja dengan ayah yang terlibat cenderung memiliki prestasi akademik lebih baik dan regulasi emosi yang stabil.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="education-card h-100">
            <h3>Tips Komunikasi</h3>
            <p>Mulailah dengan hal-hal kecil, sampaikan perasaanmu secara jujur, dan mintalah waktu luang kepada ayah untuk sekadar mengobrol.</p>
          </div>
        </div>
      @endif
    </div>

    <!-- Footer Promo Card -->
    <div class="content-card mt-4 mt-md-5 text-center d-flex flex-column align-items-center">
      <h4 class="mb-3">{!! isset($footer['footer']) && $footer['footer']->title ? $footer['footer']->title : 'Ingin tahu lebih banyak?' !!}</h4>
      <p class="mb-3 mb-md-4">{!! isset($footer['footer']) && $footer['footer']->body ? $footer['footer']->body : 'Kami menyediakan berbagai materi literasi terkait pola asuh ayah yang bisa kamu pelajari lebih lanjut.' !!}</p>
      <div>
        <a href="{{ route('ekspresi.create') }}" class="btn-hero">
          Bagikan Ceritamu
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
          </svg>
        </a>
      </div>
    </div>
  </div>
</section>
@endsection
