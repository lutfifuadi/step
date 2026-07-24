@php
$configData = Helper::appClasses();
$isFront = true;
@endphp

@extends('layouts/public-layout')

@section('title', 'Tentang STEP')

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

  .mission-list .badge-pill {
    min-width: 28px;
    height: 28px;
    width: 28px;
    padding: 0;
    border-radius: 5px;
    font-weight: 700;
    font-size: 0.8rem;
    flex-shrink: 0;
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
    <span class="badge-pill">{!! isset($hero['hero']) && $hero['hero']->icon ? $hero['hero']->icon : 'Tentang STEP' !!}</span>
    <h1 class="hero-title">{!! isset($hero['hero']) && $hero['hero']->title ? $hero['hero']->title : 'Tentang Program STEP' !!}</h1>
    <p class="hero-subtitle">{!! isset($hero['hero']) && $hero['hero']->body ? $hero['hero']->body : 'Mengenal lebih dekat visi dan misi kami untuk remaja dan keluarga.' !!}</p>
  </div>
</section>

<!-- Content Details -->
<section class="py-4 py-md-5">
  <div class="container py-2 py-md-3">
    <div class="row g-3 g-md-4">
      <div class="col-lg-6">
        <div class="content-card h-100">
          <h4 class="mb-3">{!! isset($content['background']) && $content['background']->title ? $content['background']->title : 'Latar Belakang' !!}</h4>
          @if(isset($content['background']) && $content['background']->body)
            <div class="mt-3">{!! $content['background']->body !!}</div>
          @else
            <p class="mt-3">Program STEP (Studying Teens' Expectations on Paternal Involvement) lahir dari keprihatinan terhadap fenomena <i>fatherless</i> di Indonesia. Banyak remaja merasa kehilangan arah atau kurang mendapatkan dukungan emosional dari sosok ayah.</p>
            <p>Penelitian ini bertujuan untuk memetakan apa yang sebenarnya diharapkan oleh para remaja dari ayah mereka, sehingga dapat menjadi masukan berharga bagi para orang tahu dan pendidik.</p>
          @endif
        </div>
      </div>
      
      <div class="col-lg-6">
        <div class="content-card h-100">
          <h4 class="mb-3">{!! isset($content['vision']) && $content['vision']->title ? $content['vision']->title : 'Visi Kami' !!}</h4>
          @if(isset($content['vision']) && $content['vision']->body)
            <div class="mt-3 mb-4">{!! $content['vision']->body !!}</div>
          @else
            <p class="mt-3 mb-4">Menciptakan generasi remaja yang tangguh secara emosional melalui penguatan peran ayah dalam pengasuhan.</p>
          @endif
          
          <h4 class="mb-3">Misi Kami</h4>
          <div class="d-flex flex-column gap-3 mission-list mt-3">
            @if($missions->count() > 0)
              @foreach($missions as $mission)
                <div class="d-flex align-items-start gap-3">
                  <span class="badge-pill">{!! $mission->icon !!}</span>
                  <div class="mb-0 text-muted">{!! $mission->body !!}</div>
                </div>
              @endforeach
            @else
              <div class="d-flex align-items-start gap-3">
                <span class="badge-pill">1</span>
                <p class="mb-0 text-muted">Menjadi wadah aspirasi remaja yang aman dan tersembunyi.</p>
              </div>
              <div class="d-flex align-items-start gap-3">
                <span class="badge-pill">2</span>
                <p class="mb-0 text-muted">Mengumpulkan data empiris untuk penelitian OPSI 2026.</p>
              </div>
              <div class="d-flex align-items-start gap-3">
                <span class="badge-pill">3</span>
                <p class="mb-0 text-muted">Memberikan edukasi kepada pihak sekolah dan orang tua.</p>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="text-center mt-4 mt-md-5">
      <a href="{{ route('ekspresi.create') }}" class="btn-hero">
        Buka Ruang Ekspresi
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
        </svg>
      </a>
    </div>
  </div>
</section>
@endsection
