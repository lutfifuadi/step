@php
$pageConfigs = ['layout' => 'front'];
@endphp

@extends('layouts.public-layout')

@section('title', 'Ruang Ekspresi — STEP')

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

  /* Form & Card overrides */
  .expression-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 1.25rem;
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(0, 61, 51, 0.05);
  }

  @media (min-width: 768px) {
    .expression-card {
      padding: 2.5rem;
    }
  }

  .featured-expression-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(0, 61, 51, 0.05);
    transition: transform .3s ease;
  }

  .featured-expression-card:hover {
    transform: translateY(-2px);
  }

  .form-control, .form-select {
    border-radius: 5px !important;
    border: 1px solid #d1d5db !important;
    padding: 0.75rem 1rem !important;
    font-size: 0.95rem !important;
    font-family: 'Poppins', sans-serif !important;
  }

  .form-control:focus, .form-select:focus {
    border-color: var(--teal-deep) !important;
    box-shadow: 0 0 0 0.25rem rgba(0, 61, 51, 0.15) !important;
  }

  .btn-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .6rem;
    background: var(--teal-deep);
    color: var(--white);
    font-family: 'DM Sans', sans-serif;
    font-weight: 600;
    font-size: 1rem;
    padding: .9rem 2rem;
    border-radius: var(--radius-pill);
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all .25s ease;
    box-shadow: 0 4px 24px rgba(0,61,51,.15);
  }

  .btn-submit:hover {
    background: var(--teal-mid);
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(0,61,51,.25);
    color: var(--white);
  }

  .counselor-card {
    background: var(--white);
    border-radius: 5px;
    border: 1px solid rgba(0, 61, 51, 0.08);
    transition: transform .25s ease, box-shadow .25s ease;
  }

  .counselor-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-card);
  }

  .alert-warning-custom {
    background-color: #fffbeb;
    border: 1px solid #fef3c7;
    border-radius: var(--radius-xl);
    padding: 1.25rem;
  }

  @media (min-width: 768px) {
    .alert-warning-custom {
      padding: 2rem;
    }
  }
</style>
@endsection

@section('content')
<!-- Hero Header -->
<section class="subpage-header">
  <div class="subpage-grain"></div>
  <div class="container position-relative z-2">
    <span class="badge-pill">Ruang Ekspresi</span>
    <h1 class="hero-title">Ruang Ekspresi STEP</h1>
    <p class="hero-subtitle">Ceritakan harapan, pengalaman, perasaan, atau saranmu untuk program STEP — aman dan untuk keperluan penelitian.</p>
  </div>
</section>

<section class="container py-4 py-md-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      @if(session('success'))
      <div class="alert alert-success border-0 shadow-sm rounded-1 mb-3 mb-md-4 p-3 d-flex align-items-center" style="border-radius: 5px !important;">
        <i class="icon-base ti tabler-circle-check icon-md me-2"></i>
        <span>{{ session('success') }}</span>
      </div>
      @endif

      @if($featured->count())
      <div class="mb-4 mb-md-5">
        <h5 class="fw-bold mb-3 text-teal-deep">Ekspresi Teman-Teman</h5>
        <div class="row g-3">
          @foreach($featured as $item)
          <div class="col-md-4">
            <div class="featured-expression-card h-100 d-flex flex-column justify-content-between">
              <div>
                <span class="badge bg-{{ $item->category->color ?? 'secondary' }} mb-3" style="border-radius: 5px;">{{ $item->category->name }}</span>
                <p class="small fst-italic text-muted mb-3" style="line-height: 1.6;">"{{ \Illuminate\Support\Str::limit($item->content, 100) }}"</p>
              </div>
              <p class="small text-muted mb-0 fw-semibold">— {{ $item->display_name }}, {{ $item->origin ?? 'Anonim' }}</p>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      <div class="expression-card mb-4 mb-md-5">
        <form action="{{ route('ekspresi.store') }}" method="POST" id="formEkspresi">
          @csrf
          <input type="text" name="honeypot" style="display:none;" tabindex="-1" autocomplete="off">

          <div class="mb-4 p-3 rounded bg-light border">
            <div class="form-check form-switch m-0">
              <input class="form-check-input" type="checkbox" name="is_anonymous" id="isAnonymous" value="1" checked onchange="toggleAnonymous(this.checked)">
              <label class="form-check-label fw-semibold text-dark ms-2" for="isAnonymous">Bagikan secara anonim</label>
            </div>
            <small class="text-muted d-block mt-1 ms-4">Jika diaktifkan, namamu tidak akan ditampilkan di publik.</small>
          </div>

          <div class="mb-3" id="namaField">
            <label class="form-label fw-semibold text-dark">Nama (opsional)</label>
            <input type="text" name="real_name" class="form-control @error('real_name') is-invalid @enderror" placeholder="Nama kamu..." value="{{ old('real_name') }}" disabled>
            @error('real_name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Asal / Kelas (opsional)</label>
            <input type="text" name="origin" class="form-control" placeholder="Contoh: XII IPA 2, Bandung" value="{{ old('origin') }}">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Kategori Ekspresi <span class="text-danger">*</span></label>
            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
              <option value="">-- Pilih kategori --</option>
              @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }} — {{ $cat->description }}</option>
              @endforeach
            </select>
            @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold text-dark">Curahan Hati <span class="text-danger">*</span></label>
            <textarea name="content" rows="7" class="form-control @error('content') is-invalid @enderror" placeholder="Tuliskan apa yang ada di pikiranmu... (minimal 20 karakter)" maxlength="2000" required>{{ old('content') }}</textarea>
            <div class="d-flex justify-content-between mt-2">
              @error('content')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
              <small class="text-muted ms-auto" id="charCount">{{ strlen(old('content', '')) }} / 2000</small>
            </div>
          </div>

          <div class="mb-4 p-3 rounded border border-warning bg-soft-warning" style="background-color: rgba(245, 158, 11, 0.08); border-color: rgba(245, 158, 11, 0.2) !important;">
            <div class="form-check m-0">
              <input class="form-check-input @error('consent_agreed') is-invalid @enderror" type="checkbox" name="consent_agreed" id="consentAgreed" value="1" {{ old('consent_agreed') ? 'checked' : '' }} required>
              <label class="form-check-label small text-dark ms-2" for="consentAgreed">
                Saya menyetujui bahwa data ini digunakan untuk keperluan penelitian Program STEP — OPSI 2026. Data bisa bersifat anonim, dan saya dapat menarik kontribusi ini kapan saja dengan menghubungi peneliti.
              </label>
              @error('consent_agreed')
              <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <button type="submit" class="btn-submit w-100 py-3">Kirim Ekspresi dengan Aman</button>
        </form>
      </div>

      <div class="alert-warning-custom">
        <div class="d-flex align-items-start">
          <i class="icon-base ti tabler-customer-service-2 icon-lg text-warning me-3 mt-1" style="font-size: 1.8rem;"></i>
          <div>
            <strong class="d-block mb-2 text-dark fs-5">Butuh bicara dengan seseorang?</strong>
            @if($konselorContacts->count() > 0)
              <p class="mb-3 text-muted small">Hubungi konselor sekolah atau BK MAN 1 Kota Bandung berikut untuk mendapatkan dukungan emosional secara langsung:</p>
              <div class="row g-3">
                @foreach($konselorContacts as $contact)
                  <div class="col-md-6 col-12">
                    <div class="counselor-card h-100">
                      <div class="card-body p-3">
                        <h6 class="fw-bold mb-1 text-teal-deep text-wrap">{{ $contact->name }}</h6>
                         <span class="badge bg-label-primary mb-2 small" style="border-radius: 5px;">{{ $contact->role }} ({{ $contact->institusi }})</span>
                        
                        <div class="mt-2 small text-muted">
                          <div class="mb-1 text-truncate">
                            <i class="icon-base ti tabler-phone me-1 small"></i>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->phone) }}" target="_blank" class="fw-semibold text-primary">
                              {{ $contact->phone }}
                            </a>
                          </div>
                          @if($contact->email)
                            <div class="mb-1 text-truncate">
                              <i class="icon-base ti tabler-mail me-1 small"></i>
                              <a href="mailto:{{ $contact->email }}" class="text-secondary">{{ $contact->email }}</a>
                            </div>
                          @endif
                          @if($contact->room)
                            <div class="mb-1 text-wrap">
                              <i class="icon-base ti tabler-map-pin me-1 small"></i>
                              {{ $contact->room }}
                            </div>
                          @endif
                          @if($contact->availability)
                            <div class="text-wrap">
                              <i class="icon-base ti tabler-clock me-1 small"></i>
                              {{ $contact->availability }}
                            </div>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <p class="mb-0 text-muted small">Untuk bantuan darurat, hubungi Into The Light Indonesia di 119 ext 8.</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('page-script')
<script>
function toggleAnonymous(isAnon) {
  const namaInput = document.querySelector('#namaField input');
  namaInput.disabled = isAnon;
  if (isAnon) {
    namaInput.value = '';
  }
}

const contentField = document.querySelector('textarea[name="content"]');
if (contentField) {
  contentField.addEventListener('input', function () {
    document.getElementById('charCount').textContent = this.value.length + ' / 2000';
  });
}
</script>
@endsection
