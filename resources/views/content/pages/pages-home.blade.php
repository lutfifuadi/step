@php
$configData = Helper::appClasses();
$isFront = true;
@endphp

@extends('layouts/public-layout')

@section('title', 'Beranda')

@section('page-style')
@parent
<style>
  /* ─── CSS Variables ─── */
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
    --radius-xl:    5px;
    --radius-pill:  5px;
    --shadow-card:  0 4px 32px rgba(0,61,51,.08);
  }

  /* ─── Reset & Base ─── */
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--cream);
    color: var(--text-dark);
    overflow-x: hidden;
  }

  h1, h2, h3, h4, h5 {
    font-weight: 700;
    letter-spacing: -0.02em;
  }

  /* ─── Utility ─── */
  .container-step {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 1.5rem;
  }

  .badge-pill {
    display: inline-block;
    background: var(--amber-light);
    color: #92400e;
    font-family: 'DM Sans', sans-serif;
    font-size: .75rem;
    font-weight: 500;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: .35rem 1rem;
    border-radius: var(--radius-pill);
  }

  /* ─── HERO ─── */
  .hero {
    position: relative;
    background: var(--teal-deep);
    overflow: hidden;
    padding: 7rem 0 5rem;
    min-height: 100vh;
    display: flex;
    align-items: center;
  }

  /* Decorative blobs */
  .hero::before {
    content: '';
    position: absolute;
    width: 600px; height: 600px;
    top: -150px; right: -150px;
    background: radial-gradient(circle at 40% 40%, #00695c 0%, transparent 70%);
    border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
    opacity: .45;
    animation: blobFloat 8s ease-in-out infinite alternate;
  }

  .hero::after {
    content: '';
    position: absolute;
    width: 400px; height: 400px;
    bottom: -100px; left: -80px;
    background: radial-gradient(circle, var(--amber) 0%, transparent 70%);
    border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
    opacity: .18;
    animation: blobFloat 10s ease-in-out infinite alternate-reverse;
  }

  @keyframes blobFloat {
    from { transform: scale(1) translate(0,0) rotate(0deg); }
    to   { transform: scale(1.08) translate(30px, 20px) rotate(8deg); }
  }

  /* Grain texture overlay */
  .hero-grain {
    position: absolute; inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='.04'/%3E%3C/svg%3E");
    pointer-events: none; opacity: .5;
  }

  .hero-content {
    position: relative; z-index: 2;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
  }

  .hero-left { animation: fadeUp .9s ease both; }
  .hero-right { animation: fadeUp .9s .2s ease both; }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .hero-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.15);
    color: var(--teal-soft);
    font-size: .8rem; font-weight: 500; letter-spacing: .08em; text-transform: uppercase;
    padding: .4rem 1rem; border-radius: var(--radius-pill);
    margin-bottom: 1.5rem;
    backdrop-filter: blur(8px);
  }

  .hero-eyebrow span { width: 6px; height: 6px; background: var(--amber); border-radius: 50%; animation: pulse 2s infinite; }
  @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }

  .hero-title {
    font-size: clamp(2.2rem, 5vw, 3.8rem);
    line-height: 1.1;
    color: var(--white);
    margin-bottom: 1.25rem;
  }

  .hero-title .accent { color: var(--amber); }

  .hero-subtitle {
    font-size: 1.05rem;
    color: rgba(255,255,255,.65);
    line-height: 1.75;
    max-width: 480px;
    margin-bottom: 2.5rem;
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

  .btn-hero svg { transition: transform .25s ease; }
  .btn-hero:hover svg { transform: translateX(4px); }

  /* Hero stats strip */
  .hero-stats {
    display: flex; gap: 2rem; margin-top: 3rem;
  }

  .stat-item { text-align: left; }
  .stat-num {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; font-weight: 700;
    color: var(--white);
    line-height: 1;
  }

  .stat-label {
    font-size: .8rem; color: rgba(255,255,255,.5);
    margin-top: .2rem;
  }

  /* Hero Right — feature cards */
  .hero-cards {
    display: flex; flex-direction: column; gap: 1rem;
  }

  .hero-card {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
    backdrop-filter: blur(16px);
    border-radius: var(--radius-xl);
    padding: 1.25rem 1.5rem;
    display: flex; align-items: center; gap: 1.25rem;
    transition: transform .3s ease, background .3s ease;
    cursor: default;
  }

  .hero-card:hover {
    transform: translateX(6px);
    background: rgba(255,255,255,.1);
  }

  .hero-card-icon {
    width: 52px; height: 52px; flex-shrink: 0;
    border-radius: 5px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
  }

  .icon-teal   { background: rgba(0,105,92,.5); }
  .icon-amber  { background: rgba(245,158,11,.25); }
  .icon-sky    { background: rgba(2,119,189,.25); }

  .hero-card-text h5 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; color: var(--white); margin-bottom: .25rem;
  }

  .hero-card-text p {
    font-size: .82rem; color: rgba(255,255,255,.55); line-height: 1.5;
  }

  /* ─── WAVE DIVIDER ─── */
  .wave-divider { line-height: 0; display: block; overflow: hidden; margin-bottom: -1px; }
  .wave-divider svg { display: block; width: 100%; height: auto; margin-bottom: -1px; }

  /* ─── FEATURES SECTION ─── */
  .features {
    background: var(--cream);
    padding: 6rem 0;
  }

  .features-header { text-align: center; margin-bottom: 4rem; }

  .features-header h2 {
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    color: var(--teal-deep);
    margin: .75rem 0 1rem;
  }

  .features-header p {
    color: var(--text-muted);
    max-width: 520px; margin: 0 auto;
    font-size: 1rem; line-height: 1.7;
  }

  /* Bento grid */
  .bento-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    grid-auto-rows: minmax(80px, auto);
    gap: 1.25rem;
  }

  .bento-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 2rem;
    box-shadow: var(--shadow-card);
    transition: transform .3s ease, box-shadow .3s ease;
    position: relative;
    overflow: hidden;
  }

  .bento-card::before {
    content: '';
    position: absolute;
    width: 120px; height: 120px;
    border-radius: 50%;
    top: -30px; right: -30px;
    opacity: .06;
    background: var(--teal-deep);
  }

  .bento-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 48px rgba(0,61,51,.12);
  }

  /* Grid placement */
  .bento-1 { grid-column: span 7; grid-row: span 2; background: var(--teal-deep); color: var(--white); }
  .bento-2 { grid-column: span 5; }
  .bento-3 { grid-column: span 5; }
  .bento-4 { grid-column: span 6; }
  .bento-5 { grid-column: span 6; }

  .bento-1::before { background: var(--amber); opacity: .15; width: 200px; height: 200px; top: -40px; right: -40px; }

  .bento-icon {
    width: 48px; height: 48px;
    border-radius: 5px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1.25rem;
    font-size: 1.3rem;
  }

  .icon-bg-teal   { background: rgba(0,105,92,.12); color: var(--teal-mid); }
  .icon-bg-amber  { background: var(--amber-light);  color: #92400e; }
  .icon-bg-sky    { background: var(--sky);           color: var(--sky-dark); }
  .icon-bg-white  { background: rgba(255,255,255,.12); color: var(--white); }

  .bento-card h3 {
    font-size: 1.35rem; margin-bottom: .75rem;
    color: inherit;
  }

  .bento-card p {
    font-size: .9rem; line-height: 1.7;
    color: var(--text-muted);
  }

  .bento-1 p { color: rgba(255,255,255,.65); }

  .bento-big-num {
    font-family: 'Playfair Display', serif;
    font-size: 4.5rem; font-weight: 900;
    color: var(--amber);
    line-height: 1;
    margin-bottom: .5rem;
  }

  /* ─── CTA SECTION ─── */
  .cta-section {
    background: linear-gradient(135deg, var(--teal-deep), #005c4b);
    padding: 6rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
  }

  .cta-section::before {
    content: '';
    position: absolute;
    width: 500px; height: 500px;
    background: var(--amber);
    border-radius: 50%;
    bottom: -250px; left: 50%;
    transform: translateX(-50%);
    opacity: .06;
  }

  .cta-section h2 {
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    color: var(--white);
    margin-bottom: 1rem;
  }

  .cta-section p {
    color: rgba(255,255,255,.65);
    font-size: 1rem; max-width: 480px;
    margin: 0 auto 2rem;
    line-height: 1.75;
  }

  /* ─── RESPONSIVE ─── */
  @media (max-width: 991px) {
    .hero-content { grid-template-columns: 1fr; gap: 2.5rem; }
    .hero { padding: 6rem 0 4rem; min-height: auto; }
    .hero-stats { gap: 1.25rem; }

    .bento-1 { grid-column: span 12; grid-row: span 1; }
    .bento-2, .bento-3 { grid-column: span 6; }
    .bento-4, .bento-5 { grid-column: span 6; }
  }

  @media (max-width: 767px) {
    .hero { padding: 3.5rem 0 2.5rem; min-height: auto; }
    .hero-title { font-size: 2rem; }
    .hero-subtitle { font-size: .95rem; margin-bottom: 1.5rem; }
    .hero-stats { flex-wrap: wrap; gap: 1rem; margin-top: 1.5rem; }
    .stat-num { font-size: 1.5rem; }

    .bento-2, .bento-3, .bento-4, .bento-5 { grid-column: span 12; }
    .bento-big-num { font-size: 3rem; }
    .bento-grid { gap: 0.875rem; }

    .features { padding: 3rem 0; }
    .features-header { margin-bottom: 2rem; }
    .cta-section { padding: 3rem 0; }
  }

  @media (max-width: 480px) {
    .btn-hero { font-size: .9rem; padding: .8rem 1.5rem; }
    .hero-card { flex-direction: column; text-align: center; }
    .hero-eyebrow { font-size: .72rem; }
  }
</style>
@endsection

@section('content')

<!-- ── HERO ─────────────────────────────────────────────── -->
<section class="hero">
  <div class="hero-grain"></div>
  <div class="container">
    <div class="hero-content">

      <!-- LEFT -->
      <div class="hero-left">
        <div class="hero-eyebrow">
          <span></span>
          {!! isset($hero['eyebrow']) && $hero['eyebrow']->title ? $hero['eyebrow']->title : 'Program Penelitian Remaja' !!}
        </div>

        <h1 class="hero-title">
          {!! isset($hero['title']) && $hero['title']->title ? $hero['title']->title : 'Suara Remaja untuk <span class="accent">Keterlibatan Ayah</span> yang Lebih Bermakna' !!}
        </h1>

        <p class="hero-subtitle">
          {!! isset($hero['subtitle']) && $hero['subtitle']->body ? $hero['subtitle']->body : 'STEP adalah ruang aman bagi remaja untuk mengekspresikan harapan, cerita, dan aspirasi mengenai peran ayah. Identitasmu terjaga sepenuhnya.' !!}
        </p>

        <a href="{{ route('ekspresi.create') }}" class="btn-hero">
          {!! isset($hero['button_text']) && $hero['button_text']->title ? $hero['button_text']->title : 'Buka Ruang Ekspresi' !!}
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
          </svg>
        </a>

        <div class="hero-stats">
          @if(isset($stats['stat_1']))
            <div class="stat-item">
              <div class="stat-num">{!! $stats['stat_1']->title !!}</div>
              <div class="stat-label">{!! $stats['stat_1']->body !!}</div>
            </div>
          @else
            <div class="stat-item">
              <div class="stat-num">100%</div>
              <div class="stat-label">Anonim & Aman</div>
            </div>
          @endif

          @if(isset($stats['stat_2']))
            <div class="stat-item" style="border-left:1px solid rgba(255,255,255,.15); padding-left:2rem">
              <div class="stat-num">{!! $stats['stat_2']->title !!}</div>
              <div class="stat-label">{!! $stats['stat_2']->body !!}</div>
            </div>
          @else
            <div class="stat-item" style="border-left:1px solid rgba(255,255,255,.15); padding-left:2rem">
              <div class="stat-num">Gratis</div>
              <div class="stat-label">Tanpa Biaya</div>
            </div>
          @endif

          @if(isset($stats['stat_3']))
            <div class="stat-item" style="border-left:1px solid rgba(255,255,255,.15); padding-left:2rem">
              <div class="stat-num">{!! $stats['stat_3']->title !!}</div>
              <div class="stat-label">{!! $stats['stat_3']->body !!}</div>
            </div>
          @else
            <div class="stat-item" style="border-left:1px solid rgba(255,255,255,.15); padding-left:2rem">
              <div class="stat-num">Real</div>
              <div class="stat-label">Riset Nyata</div>
            </div>
          @endif
        </div>
      </div>

      <!-- RIGHT -->
      <div class="hero-right">
        <div class="hero-cards">

          @if(isset($cards['card_1']))
            <div class="hero-card">
              <div class="hero-card-icon icon-teal">
                {!! $cards['card_1']->icon ?? '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#b2dfdb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>' !!}
              </div>
              <div class="hero-card-text">
                <h5>{!! $cards['card_1']->title !!}</h5>
                <p>{!! $cards['card_1']->body !!}</p>
              </div>
            </div>
          @else
            <div class="hero-card">
              <div class="hero-card-icon icon-teal">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#b2dfdb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
              </div>
              <div class="hero-card-text">
                <h5>Aman &amp; Anonim</h5>
                <p>Ekspresikan perasaanmu tanpa khawatir. Identitasmu tetap sepenuhnya terjaga.</p>
              </div>
            </div>
          @endif

          @if(isset($cards['card_2']))
            <div class="hero-card">
              <div class="hero-card-icon icon-amber">
                {!! $cards['card_2']->icon ?? '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>' !!}
              </div>
              <div class="hero-card-text">
                <h5>{!! $cards['card_2']->title !!}</h5>
                <p>{!! $cards['card_2']->body !!}</p>
              </div>
            </div>
          @else
            <div class="hero-card">
              <div class="hero-card-icon icon-amber">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
              </div>
              <div class="hero-card-text">
                <h5>Media Aspirasi</h5>
                <p>Sampaikan harapan dan saranmu mengenai peran ayah dalam hidupmu.</p>
              </div>
            </div>
          @endif

          @if(isset($cards['card_3']))
            <div class="hero-card">
              <div class="hero-card-icon icon-sky">
                {!! $cards['card_3']->icon ?? '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#7ec8e3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>' !!}
              </div>
              <div class="hero-card-text">
                <h5>{!! $cards['card_3']->title !!}</h5>
                <p>{!! $cards['card_3']->body !!}</p>
              </div>
            </div>
          @else
            <div class="hero-card">
              <div class="hero-card-icon icon-sky">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#7ec8e3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
              </div>
              <div class="hero-card-text">
                <h5>Kontribusi Nyata</h5>
                <p>Data yang terkumpul menjadi basis penelitian untuk generasi yang lebih baik.</p>
              </div>
            </div>
          @endif

        </div>
      </div>

    </div>
  </div>
</section>

<!-- Wave -->
<div class="wave-divider" style="background:var(--teal-deep)">
  <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
    <path d="M0,80 C360,0 1080,80 1440,0 L1440,80 Z" fill="#fdf6ee"/>
  </svg>
</div>

<!-- ── FEATURES ─────────────────────────────────────────── -->
<section class="features">
    <div class="container">

    <div class="features-header">
      <span class="badge-pill">{!! isset($featuresHeader['header']) && $featuresHeader['header']->icon ? $featuresHeader['header']->icon : 'Mengapa STEP Penting?' !!}</span>
      <h2>{!! isset($featuresHeader['header']) && $featuresHeader['header']->title ? $featuresHeader['header']->title : 'Membangun Jembatan antara<br>Remaja dan Ayah' !!}</h2>
      <p>{!! isset($featuresHeader['header']) && $featuresHeader['header']->body ? $featuresHeader['header']->body : 'Kehadiran ayah terbukti berpengaruh besar pada perkembangan mental dan emosional remaja. Mari kita suarakan bersama.' !!}</p>
    </div>

    <!-- Bento Grid -->
    <div class="bento-grid">

      <!-- Card 1 — Large dark card -->
      <div class="bento-card bento-1">
        <div class="bento-icon icon-bg-white">
          {!! isset($bento['bento_1']) && $bento['bento_1']->icon ? $bento['bento_1']->icon : '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>' !!}
        </div>
        <h3>{!! isset($bento['bento_1']) && $bento['bento_1']->title ? $bento['bento_1']->title : 'Edukasi Peran Ayah' !!}</h3>
        <p class="mb-0">
          {!! isset($bento['bento_1']) && $bento['bento_1']->body ? $bento['bento_1']->body : 'Memberikan pemahaman mendalam tentang betapa krusialnya kehadiran ayah bagi perkembangan mental dan emosional remaja. Keterlibatan aktif ayah menciptakan fondasi kepercayaan diri yang kuat sejak dini.' !!}
        </p>
        <div class="mt-3 d-flex gap-2 flex-wrap">
          <span style="background:rgba(245,158,11,.2); color:var(--amber); font-size:.75rem; font-weight:500; padding:.3rem .9rem; border-radius:5px;">Perkembangan Emosional</span>
          <span style="background:rgba(255,255,255,.1); color:rgba(255,255,255,.7); font-size:.75rem; font-weight:500; padding:.3rem .9rem; border-radius:5px;">Kesehatan Mental</span>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="bento-card bento-2">
        <div class="bento-icon icon-bg-amber">
          {!! isset($bento['bento_2']) && $bento['bento_2']->icon ? $bento['bento_2']->icon : '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>' !!}
        </div>
        <h3>{!! isset($bento['bento_2']) && $bento['bento_2']->title ? $bento['bento_2']->title : 'Kesehatan Mental' !!}</h3>
        <p>{!! isset($bento['bento_2']) && $bento['bento_2']->body ? $bento['bento_2']->body : 'Membantu remaja mengidentifikasi perasaan dan memberikan ruang aman untuk bercerita tanpa penilaian.' !!}</p>
      </div>

      <!-- Card 3 -->
      <div class="bento-card bento-3">
        <div class="bento-icon icon-bg-sky">
          {!! isset($bento['bento_3']) && $bento['bento_3']->icon ? $bento['bento_3']->icon : '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>' !!}
        </div>
        <h3>{!! isset($bento['bento_3']) && $bento['bento_3']->title ? $bento['bento_3']->title : 'Pencegahan Dini' !!}</h3>
        <p>{!! isset($bento['bento_3']) && $bento['bento_3']->body ? $bento['bento_3']->body : 'Mengidentifikasi sejak dini tanda-tanda <em>fatherless</em> dan dampaknya bagi perilaku remaja di lingkungan sosial.' !!}</p>
      </div>

      <!-- Card 4 — big number -->
      <div class="bento-card bento-4" style="background:var(--amber-light);">
        <div class="bento-big-num">{!! isset($bento['bento_4']) && $bento['bento_4']->title ? $bento['bento_4']->title : '72%' !!}</div>
        <h3 style="color:var(--teal-deep)">Remaja Inginkan Ayah Lebih Hadir</h3>
        <p>{!! isset($bento['bento_4']) && $bento['bento_4']->body ? $bento['bento_4']->body : 'Data awal menunjukkan mayoritas remaja berharap ayah lebih aktif terlibat dalam kehidupan sehari-hari mereka.' !!}</p>
      </div>

      <!-- Card 5 -->
      <div class="bento-card bento-5">
        <div class="bento-icon icon-bg-teal">
          {!! isset($bento['bento_5']) && $bento['bento_5']->icon ? $bento['bento_5']->icon : '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>' !!}
        </div>
        <h3>{!! isset($bento['bento_5']) && $bento['bento_5']->title ? $bento['bento_5']->title : 'Keterlibatan Aktif' !!}</h3>
        <p>{!! isset($bento['bento_5']) && $bento['bento_5']->body ? $bento['bento_5']->body : 'Mendorong ayah terlibat lebih aktif dalam kegiatan sehari-hari dan pengambilan keputusan penting bagi remaja.' !!}</p>
      </div>

    </div>
  </div>
</section>

<!-- ── CTA ──────────────────────────────────────────────── -->
<section class="cta-section">
  <div class="container" style="position:relative; z-index:2">
    <span class="badge-pill" style="background:rgba(255,255,255,.1); color:rgba(255,255,255,.8); border:1px solid rgba(255,255,255,.2);">{!! isset($cta['cta']) && $cta['cta']->icon ? $cta['cta']->icon : 'Bergabung Sekarang' !!}</span>
    <h2 style="margin-top:.75rem">{!! isset($cta['cta']) && $cta['cta']->title ? $cta['cta']->title : 'Suaramu Berarti.<br>Masa Depan Dimulai dari Sini.' !!}</h2>
    <p>{!! isset($cta['cta']) && $cta['cta']->body ? $cta['cta']->body : 'Tidak butuh waktu lama. Cukup beberapa menit untuk berbagi pengalamanmu dan membantu riset yang bermakna.' !!}</p>
    <a href="{{ route('ekspresi.create') }}" class="btn-hero" style="font-size:1rem;">
      Mulai Ekspresikan Dirimu
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
      </svg>
    </a>
  </div>
</section>

@endsection