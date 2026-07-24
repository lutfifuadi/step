@php
$pageConfigs = ['layout' => 'front'];
@endphp

@extends('layouts.public-layout')

@section('title', 'Terima Kasih — STEP')

@section('content')
<section class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow-sm p-5 text-center">
        <div class="mb-4">
          <div class="rounded-circle bg-success text-white mx-auto d-flex align-items-center justify-content-center mb-3" style="width:80px; height:80px;">
            <i class="icon-base ti tabler-shield-check icon-xl"></i>
          </div>
          <h2 class="fw-bold">Terima kasih telah berbagi!</h2>
          <p class="text-muted">Ceritamu sudah tersimpan dengan aman dan akan membantu penelitian Program STEP — OPSI 2026.</p>
        </div>

        <div class="alert alert-info text-start mb-4">
          <p class="mb-2 fw-semibold"><i class="icon-base ti tabler-info-circle me-2"></i>Yang perlu kamu tahu:</p>
          <ul class="mb-0 small">
            <li>Data hanya digunakan untuk penelitian akademik.</li>
            <li>Identitasmu terlindungi jika memilih anonim.</li>
            <li>Kamu bisa menarik kontribusi kapan saja.</li>
          </ul>
        </div>

        <div class="d-grid gap-2">
          <a href="{{ route('ekspresi.create') }}" class="btn btn-primary">Bagikan lagi</a>
          <a href="{{ route('pages-home') }}" class="btn btn-outline-secondary">Kembali ke Beranda</a>
        </div>

        <hr class="my-4">
        
        <div class="text-start">
          <strong class="d-block mb-3 text-center"><i class="icon-base ti tabler-customer-service-2 me-1"></i>Butuh dukungan atau ingin bicara dengan seseorang?</strong>
          @if($konselorContacts->count() > 0)
            <div class="row g-3 justify-content-center">
              @foreach($konselorContacts as $contact)
                <div class="col-12 text-start">
                  <div class="card border shadow-none bg-light">
                    <div class="card-body p-3">
                      <h6 class="fw-bold mb-1 text-teal-deep">{{ $contact->name }}</h6>
                      <span class="badge bg-label-primary mb-2 small">{{ $contact->role }} ({{ $contact->institusi }})</span>
                      
                      <div class="mt-2 small text-muted">
                        <div>
                          <i class="icon-base ti tabler-phone me-1 small"></i>
                          Hubungi: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->phone) }}" target="_blank" class="fw-semibold text-primary">
                            {{ $contact->phone }}
                          </a>
                        </div>
                        @if($contact->room)
                          <div>
                            <i class="icon-base ti tabler-map-pin me-1 small"></i>
                            Ruang: {{ $contact->room }}
                          </div>
                        @endif
                        @if($contact->availability)
                          <div>
                            <i class="icon-base ti tabler-clock me-1 small"></i>
                            Layanan: {{ $contact->availability }}
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-muted small text-center mb-0">Untuk bantuan darurat, hubungi <strong>Into The Light Indonesia</strong> di <strong>119 ext 8</strong>.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
