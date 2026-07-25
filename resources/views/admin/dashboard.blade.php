@extends('layouts.admin-layout')

@section('title', 'Admin Dashboard')

@section('content')
@php
  use Illuminate\Support\Str;
@endphp
<div class="container-fluid py-2">
  <!-- Welcome Header Card -->
  <div class="card border-0 mb-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--teal-deep) 0%, var(--teal-mid) 100%); border-radius: 5px; box-shadow: var(--shadow-card);">
    <!-- Decorative Circle Blobs inside the card -->
    <div class="position-absolute" style="width: 250px; height: 250px; background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%); top: -70px; right: -50px; border-radius: 50%;"></div>
    <div class="position-absolute" style="width: 150px; height: 150px; background: radial-gradient(circle, var(--amber) 0%, transparent 70%); bottom: -40px; right: 100px; border-radius: 50%; opacity: 0.15;"></div>
    
    <div class="card-body p-4 p-md-5 position-relative">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <span class="badge bg-warning text-dark mb-3 px-3 py-2 text-uppercase font-semibold tracking-wider" style="font-size: 0.75rem; border-radius: 30px;">Panel Admin STEP</span>
          <h3 class="fw-bold mb-2 text-warning">Selamat Datang, Admin STEP!</h3>
          <p class="mb-0 text-white-50" style="font-size: 0.95rem; max-width: 600px;">
            Kelola data ekspresi remaja, pantau pengaduan/konseling, serta sesuaikan informasi landing page untuk mendukung paternal involvement di Indonesia.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- 5 Card Stats Grid -->
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4 mb-4">
    <div class="col">
      <div class="step-stat-card h-100">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="step-stat-card__title">Total Ekspresi</span>
          <span class="badge p-2 rounded-circle" style="background-color: rgba(0, 105, 92, 0.1); color: var(--teal-mid);">
            <i class="icon-base ti tabler-message-2 fs-4"></i>
          </span>
        </div>
        <h3 class="step-stat-card__value">{{ $stats['total'] }}</h3>
        <span class="text-muted small mt-2">Semua status</span>
      </div>
    </div>
    <div class="col">
      <div class="step-stat-card h-100">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="step-stat-card__title">Menunggu Moderasi</span>
          <span class="badge p-2 rounded-circle" style="background-color: rgba(245, 158, 11, 0.1); color: var(--amber);">
            <i class="icon-base ti tabler-clock fs-4"></i>
          </span>
        </div>
        <h3 class="step-stat-card__value text-warning">{{ $stats['pending'] }}</h3>
        <span class="text-muted small mt-2">Perlu persetujuan</span>
      </div>
    </div>
    <div class="col">
      <div class="step-stat-card h-100">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="step-stat-card__title">Disetujui</span>
          <span class="badge p-2 rounded-circle" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745;">
            <i class="icon-base ti tabler-circle-check fs-4"></i>
          </span>
        </div>
        <h3 class="step-stat-card__value text-success">{{ $stats['approved'] }}</h3>
        <span class="text-muted small mt-2">Tampil di beranda</span>
      </div>
    </div>
    <div class="col">
      <div class="step-stat-card h-100">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="step-stat-card__title">Flagged</span>
          <span class="badge p-2 rounded-circle" style="background-color: rgba(220, 53, 69, 0.1); color: #dc3545;">
            <i class="icon-base ti tabler-flag fs-4"></i>
          </span>
        </div>
        <h3 class="step-stat-card__value text-danger">{{ $stats['flagged'] }}</h3>
        <span class="text-muted small mt-2">Konten berisiko</span>
      </div>
    </div>
    <div class="col">
      <div class="step-stat-card h-100">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="step-stat-card__title">Hari Ini</span>
          <span class="badge p-2 rounded-circle" style="background-color: rgba(2, 119, 189, 0.1); color: var(--sky-dark);">
            <i class="icon-base ti tabler-calendar fs-4"></i>
          </span>
        </div>
        <h3 class="step-stat-card__value text-info">{{ $stats['today'] }}</h3>
        <span class="text-muted small mt-2">Ekspresi baru</span>
      </div>
    </div>
  </div>

  <!-- Aksi Cepat & Tabel Moderasi Terbaru -->
  <div class="row g-4">
    <!-- Aksi Cepat Card -->
    <div class="col-lg-4">
      <div class="card border-0 h-100" style="border-radius: 5px; box-shadow: var(--shadow-card); background-color: var(--white);">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-3 text-teal-deep" style="color: var(--teal-deep);">Aksi Cepat</h5>
          <p class="text-muted small mb-4">Akses pintasan berikut untuk melakukan tugas administratif utama secara efisien.</p>
          <div class="d-flex flex-column gap-3">
            <a href="{{ route('admin.expressions.index') }}" class="step-quick-btn step-quick-btn--primary">
              <i class="icon-base ti tabler-shield-check fs-5"></i>
              <span>Moderasi Ekspresi</span>
            </a>
            <a href="{{ route('admin.konselor.index') }}" class="step-quick-btn">
              <i class="icon-base ti tabler-phone fs-5"></i>
              <span>Kontak Konselor</span>
            </a>
            <a href="{{ route('admin.program-contents.index') }}" class="step-quick-btn">
              <i class="icon-base ti tabler-edit fs-5"></i>
              <span>Konten Landing Page</span>
            </a>
            <button type="button" class="step-quick-btn" data-bs-toggle="modal" data-bs-target="#clearCacheModal">
              <i class="icon-base ti tabler-refresh fs-5"></i>
              <span>Clear Cache</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Moderasi Terbaru -->
    <div class="col-lg-8">
      <div class="card border-0 h-100" style="border-radius: 5px; box-shadow: var(--shadow-card); background-color: var(--white);">
        <div class="card-body p-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <h5 class="fw-bold mb-1 text-teal-deep" style="color: var(--teal-deep);">Menunggu Moderasi</h5>
              <p class="text-muted small mb-0">5 ekspresi terbaru yang membutuhkan persetujuan/flag dari admin.</p>
            </div>
            <a href="{{ route('admin.expressions.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 30px; font-size: 0.8rem; color: var(--teal-mid); border-color: var(--teal-soft);">
              Lihat Semua
            </a>
          </div>

          @if($recentExpressions->count())
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead>
                  <tr class="text-muted" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <th class="ps-0">ID</th>
                    <th>Nama Tampil</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th class="text-end pe-0">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($recentExpressions as $expression)
                    <tr>
                      <td class="ps-0 fw-semibold text-muted">#{{ $expression->id }}</td>
                      <td>
                        <div class="d-flex flex-column">
                          <span class="fw-semibold text-dark">{{ $expression->display_name }}</span>
                          <span class="text-muted small text-truncate" style="max-width: 250px;">{{ Str::limit($expression->content, 45) }}</span>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-light text-dark border" style="border-radius: 6px;">{{ $expression->category?->name ?? '-' }}</span>
                      </td>
                      <td>
                        <span class="badge bg-warning text-dark" style="border-radius: 30px; font-size: 0.75rem; padding: 4px 10px;">
                          {{ ucfirst($expression->status) }}
                        </span>
                      </td>
                      <td class="text-end pe-0">
                        <a href="{{ route('admin.expressions.show', $expression) }}" class="btn btn-sm text-white py-1 px-3" style="border-radius: 8px; background-color: var(--teal-mid); border-color: var(--teal-mid);">
                          Detail
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-5">
              <div class="mb-3 text-muted">
                <i class="icon-base ti tabler-mood-smile fs-1"></i>
              </div>
              <h6 class="fw-bold mb-1">Semua Bersih!</h6>
              <p class="text-muted small mb-0">Tidak ada ekspresi baru yang menunggu moderasi saat ini.</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

<!-- Clear Cache Modal -->
<div class="modal fade" id="clearCacheModal" tabindex="-1" aria-labelledby="clearCacheModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
      
      <!-- Modal Header -->
      <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, var(--teal-deep) 0%, var(--teal-mid) 100%); padding: 1.5rem 1.5rem 1rem;">
        <div class="d-flex align-items-center gap-3">
          <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 44px; height: 44px; background: rgba(255,255,255,0.15);">
            <i class="icon-base ti tabler-refresh text-white" style="font-size: 1.3rem;"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="clearCacheModalLabel" style="font-size: 1.1rem;">Bersihkan Cache</h5>
            <small class="text-white-50" style="font-size: 0.8rem;">Clear Application Cache</small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.7;"></button>
      </div>
      
      <!-- Modal Body -->
      <div class="modal-body px-4 py-4">
        <p class="mb-3" style="font-size: 0.95rem; color: #444; line-height: 1.7;">
          Semua cache aplikasi akan dibersihkan, termasuk:
        </p>
        <ul class="mb-3 ps-3" style="font-size: 0.875rem; color: #555; line-height: 2;">
          <li>Configuration cache</li>
          <li>Route cache</li>
          <li>Compiled views</li>
          <li>Event cache</li>
        </ul>
        <div class="p-3 rounded-3" style="background-color: #fef3c7; border: 1px solid #fcd34d;">
          <div class="d-flex align-items-start gap-2">
            <i class="icon-base ti tabler-alert-triangle mt-1" style="color: #d97706; font-size: 1.1rem;"></i>
            <small style="color: #92400e; line-height: 1.6;">
              <strong>Catatan:</strong> CSS dan pengaturan akan diperbarui setelah cache dibersihkan. Proses ini aman dan tidak menghapus data pengguna.
            </small>
          </div>
        </div>
      </div>
      
      <!-- Modal Footer -->
      <div class="modal-footer border-0 px-4 pb-4 pt-0">
        <div class="d-flex gap-2 w-100">
          <button type="button" class="btn flex-grow-1 fw-semibold" 
            style="border-radius: 8px; padding: 0.65rem 1rem; font-size: 0.9rem; background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;"
            data-bs-dismiss="modal">
            Batal
          </button>
          <form method="POST" action="{{ route('admin.clear-cache') }}" class="flex-grow-1 m-0" id="clear-cache-form-modal">
            @csrf
            <button type="submit" class="btn w-100 fw-semibold text-white" 
              style="border-radius: 8px; padding: 0.65rem 1rem; font-size: 0.9rem; background: linear-gradient(135deg, var(--teal-deep) 0%, var(--teal-mid) 100%); border: none; box-shadow: 0 4px 12px rgba(0, 61, 51, 0.25);">
              <i class="icon-base ti tabler-check me-1"></i> Ya, Bersihkan
            </button>
          </form>
        </div>
      </div>
      
    </div>
  </div>
</div>

@endsection
