@php
$configData = Helper::appClasses();
$isFront = true;
@endphp

@extends('layouts/public-layout')

@section('title', 'Pencegahan STEP')

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

  .content-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    box-shadow: var(--shadow-card);
    transition: transform .3s ease, box-shadow .3s ease;
    border: 1px solid rgba(0, 61, 51, 0.05);
  }

  @media (min-width: 768px) {
    .content-card {
      padding: 2.5rem;
    }
  }

  .content-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 48px rgba(0,61,51,.12);
  }

  .content-card h4 {
    color: var(--teal-deep);
    font-weight: 700;
    font-size: 1.5rem;
    position: relative;
    padding-bottom: 0.75rem;
  }

  .content-card h4::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    background-color: var(--amber);
    border-radius: 2px;
  }

  .content-card p {
    color: var(--text-muted);
    font-size: 0.95rem;
    line-height: 1.75;
  }

  /* Info cards styling matching bento grid cards */
  .info-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 1.25rem;
    box-shadow: var(--shadow-card);
    transition: transform .3s ease, box-shadow .3s ease;
    border: 1px solid rgba(0, 61, 51, 0.05);
    position: relative;
    overflow: hidden;
  }

  @media (min-width: 768px) {
    .info-card {
      padding: 1.75rem;
    }
  }

  .info-card::before {
    content: '';
    position: absolute;
    width: 80px; height: 80px;
    border-radius: 50%;
    top: -20px; right: -20px;
    opacity: .04;
    background: var(--teal-deep);
  }

  .info-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 48px rgba(0,61,51,.12);
  }

  .info-card h5 {
    color: var(--teal-deep);
    font-weight: 700;
    font-size: 1.15rem;
    margin-bottom: 0.75rem;
  }

  .info-card p {
    color: var(--text-muted);
    font-size: 0.88rem;
    line-height: 1.6;
    margin-bottom: 0;
  }

  .prevention-steps .badge-pill {
    min-width: 28px;
    height: 28px;
    width: 28px;
    padding: 0;
    border-radius: 5px;
    font-weight: 700;
    font-size: 0.8rem;
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
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
    <span class="badge-pill">{!! isset($hero['hero']) && $hero['hero']->icon ? $hero['hero']->icon : 'Pencegahan STEP' !!}</span>
    <h1 class="hero-title">{!! isset($hero['hero']) && $hero['hero']->title ? $hero['hero']->title : 'Pencegahan Dampak <i>Fatherless</i>' !!}</h1>
    <p class="hero-subtitle">{!! isset($hero['hero']) && $hero['hero']->body ? $hero['hero']->body : 'Langkah-langkah strategis untuk menjaga kesehatan mental remaja.' !!}</p>
  </div>
</section>

<!-- Content Details -->
<section class="py-4 py-md-5">
  <div class="container py-2 py-md-3">
    <div class="row g-3 g-md-4 align-items-stretch">
      <div class="col-lg-6">
        <div class="content-card h-100">
          <h4 class="mb-3">{!! isset($main['intro']) && $main['intro']->title ? $main['intro']->title : 'Mengapa Perlu Pencegahan?' !!}</h4>
          @if(isset($main['intro']) && $main['intro']->body)
            <div class="mt-3">{!! $main['intro']->body !!}</div>
          @else
            <p class="mt-3">Fenomena lack of fathering atau absennya peran ayah dapat memicu masalah perilaku, rendahnya kepercayaan diri, hingga depresi pada remaja. STEP hadir untuk membantu mengidentifikasi risiko ini lebih dini.</p>
          @endif

          <div class="d-flex flex-column gap-3 prevention-steps mt-4">
            @if($steps->count() > 0)
              @foreach($steps as $step)
                <div class="d-flex align-items-start gap-3">
                  <span class="badge-pill">{!! $step->icon !!}</span>
                  <div class="mb-0 text-muted">{!! $step->title !!}</div>
                </div>
              @endforeach
            @else
              <div class="d-flex align-items-start gap-3">
                <span class="badge-pill">1</span>
                <p class="mb-0 text-muted">Peningkatan Literasi Pengasuhan</p>
              </div>
              <div class="d-flex align-items-start gap-3">
                <span class="badge-pill">2</span>
                <p class="mb-0 text-muted">Ruang Aman <i>Curhat</i> Anonim</p>
              </div>
              <div class="d-flex align-items-start gap-3">
                <span class="badge-pill">3</span>
                <p class="mb-0 text-muted">Koneksi dengan Guru BK/Konselor</p>
              </div>
            @endif
          </div>
        </div>
      </div>
      
      <div class="col-lg-6">
        <div class="row g-3 g-md-4 h-100">
          @if($cards->count() > 0)
            @foreach($cards as $card)
              <div class="col-sm-6">
                <div class="info-card h-100">
                  <h5>{!! $card->title !!}</h5>
                  <p>{!! $card->body !!}</p>
                </div>
              </div>
            @endforeach
          @else
            <div class="col-sm-6">
              <div class="info-card h-100">
                <h5>Self-Awareness</h5>
                <p>Mengenali tanda-tanda stres atau kesedihan akibat kurangnya perhatian orang tua.</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-card h-100">
                <h5>Positive Coping</h5>
                <p>Menyalurkan emosi melalui kegiatan positif seperti olahraga atau menulis di STEP.</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-card h-100">
                <h5>Healthy Circle</h5>
                <p>Membangun pertemanan yang mendukung dan saling menguatkan.</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-card h-100">
                <h5>Openness</h5>
                <p>Keberanian untuk mengungkapkan harapan kepada ayah secara langsung atau melalui media.</p>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="text-center mt-4 mt-md-5">
      <a href="{{ route('ekspresi.create') }}" class="btn-hero">
        Mulai Pencegahan
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
        </svg>
      </a>
    </div>
  </div>
</section>
@endsection
