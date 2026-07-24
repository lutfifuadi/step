@extends('layouts.admin-layout')

@section('title', 'Detail Ekspresi #' . $expression->id)

@section('content')
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card shadow-sm" style="border-radius: 5px;">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
      <div>
        <h4 class="mb-1 fw-bold text-teal-deep">Detail Ekspresi #{{ $expression->id }}</h4>
        <p class="text-muted small mb-0">Halaman peninjauan dan moderasi untuk ekspresi dari pengguna.</p>
      </div>
      <a href="{{ route('admin.expressions.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i class="icon-base ti tabler-arrow-left"></i> Kembali
      </a>
    </div>

    <div class="card-body">
      <!-- Detail Field secara Rapi -->
      <div class="row g-4 mb-4">
        <!-- Kategori -->
        <div class="col-md-6 col-lg-3">
          <div class="p-3 border rounded bg-light h-100">
            <label class="form-label fw-bold text-teal-deep small mb-1">Kategori</label>
            <div class="text-dark fw-semibold">
              {{ $expression->category?->name ?? '-' }}
            </div>
          </div>
        </div>

        <!-- Nama Tampil -->
        <div class="col-md-6 col-lg-3">
          <div class="p-3 border rounded bg-light h-100">
            <label class="form-label fw-bold text-teal-deep small mb-1">Nama Tampil</label>
            <div class="text-dark fw-semibold">
              {{ $expression->display_name }}
            </div>
          </div>
        </div>

        <!-- Asal -->
        <div class="col-md-6 col-lg-3">
          <div class="p-3 border rounded bg-light h-100">
            <label class="form-label fw-bold text-teal-deep small mb-1">Asal</label>
            <div class="text-dark fw-semibold">
              {{ $expression->origin ?? '-' }}
            </div>
          </div>
        </div>

        <!-- Status -->
        <div class="col-md-6 col-lg-3">
          <div class="p-3 border rounded bg-light h-100">
            <label class="form-label fw-bold text-teal-deep small mb-1">Status</label>
            <div>
              @if($expression->status === 'approved')
                <span class="badge bg-label-success">Disetujui</span>
              @elseif($expression->status === 'flagged')
                <span class="badge bg-label-danger">Diflag</span>
              @else
                <span class="badge bg-label-warning">Tertunda</span>
              @endif
            </div>
          </div>
        </div>

        <!-- Tanggal Kirim -->
        <div class="col-md-6">
          <div class="p-3 border rounded bg-light h-100">
            <label class="form-label fw-bold text-teal-deep small mb-1">Tanggal Kirim</label>
            <div class="text-dark">
              {{ $expression->created_at->format('d F Y - H:i') }}
            </div>
          </div>
        </div>

        <!-- Persetujuan Riset -->
        <div class="col-md-6">
          <div class="p-3 border rounded bg-light h-100">
            <label class="form-label fw-bold text-teal-deep small mb-1">Persetujuan Riset</label>
            <div>
              @if($expression->consent_agreed_at)
                <span class="text-success fw-semibold d-flex align-items-center gap-1">
                  <i class="icon-base ti tabler-circle-check fs-5"></i> Setuju ({{ $expression->consent_agreed_at->format('d M Y H:i') }})
                </span>
              @else
                <span class="text-danger fw-semibold d-flex align-items-center gap-1">
                  <i class="icon-base ti tabler-circle-x fs-5"></i> Tidak Setuju
                </span>
              @endif
            </div>
          </div>
        </div>

        <!-- Flag Risiko jika Ada -->
        @if($expression->is_risky)
          <div class="col-12">
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-0" role="alert">
              <i class="icon-base ti tabler-alert-triangle fs-4"></i>
              <div>
                <strong>Terdeteksi Berisiko!</strong> Sistem mendeteksi kata kunci berisiko: 
                <span class="badge bg-danger ms-1">{{ implode(', ', $expression->risk_keywords ?? []) }}</span>
              </div>
            </div>
          </div>
        @endif

        <!-- Isi Curahan Hati -->
        <div class="col-12">
          <div class="p-3 border rounded" style="background-color: var(--cream); border-color: var(--teal-soft) !important;">
            <label class="form-label fw-bold text-teal-deep small mb-2 d-block">Isi Curahan Hati</label>
            <div class="text-dark bg-white p-3 rounded border" style="white-space: pre-wrap; font-size: 1rem; line-height: 1.6;">{{ $expression->content }}</div>
          </div>
        </div>

        <!-- Catatan Moderasi -->
        <div class="col-12">
          <div class="p-3 border rounded bg-light">
            <label class="form-label fw-bold text-teal-deep small mb-1 d-block">Catatan Moderasi</label>
            <div class="text-muted">
              {{ $expression->moderation_note ?? $expression->catatan_moderasi ?? 'Belum ada catatan moderasi.' }}
            </div>
          </div>
        </div>
      </div>

      <!-- Tombol Aksi Moderasi -->
      <div class="border-top pt-4 mt-4">
        <h5 class="fw-bold text-teal-deep mb-3 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-shield-check"></i> Kontrol Moderasi
        </h5>

        @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <div class="row g-4">
          <!-- Aksi Persetujuan & Penghapusan -->
          <div class="col-md-6">
            <div class="card h-100 border p-3 bg-white" style="border-radius: 5px;">
              <h6 class="fw-bold text-teal-deep mb-2">Tindakan Cepat</h6>
              <p class="text-muted small mb-3">Pilih tindakan cepat untuk menyetujui agar ekspresi dipublikasikan ke beranda atau menghapusnya secara permanen.</p>
              
              <div class="d-flex flex-wrap gap-2">
                <!-- Form Setujui -->
                @if($expression->status !== 'approved')
                  <form action="{{ route('admin.expressions.approve', $expression) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-success d-flex align-items-center gap-2">
                      <i class="icon-base ti tabler-check"></i> Setujui
                    </button>
                  </form>
                @else
                  <button class="btn btn-success d-flex align-items-center gap-2" disabled>
                    <i class="icon-base ti tabler-circle-check"></i> Sudah Disetujui
                  </button>
                @endif

                <!-- Form Hapus -->
                <form action="{{ route('admin.expressions.destroy', $expression) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ekspresi ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger d-flex align-items-center gap-2">
                    <i class="icon-base ti tabler-trash"></i> Hapus
                  </button>
                </form>
              </div>
            </div>
          </div>

          <!-- Aksi Flag dengan Catatan/Alasan -->
          <div class="col-md-6">
            <div class="card h-100 border p-3 bg-white" style="border-radius: 5px;">
              <h6 class="fw-bold text-teal-deep mb-2">Tandai/Flag Ekspresi</h6>
              <p class="text-muted small mb-3">Tandai ekspresi ini dengan alasan/catatan tertentu. Status ekspresi akan berubah menjadi "Diflag" dan tidak dipublikasikan.</p>
              
              <form action="{{ route('admin.expressions.flag', $expression) }}" method="POST" class="m-0">
                @csrf
                <div class="mb-3">
                  <textarea name="note" id="note" class="form-control bg-light" rows="2" required placeholder="Masukkan alasan flag (minimal 10 karakter)..." style="font-size: 0.85rem;">{{ old('note', $expression->moderation_note) }}</textarea>
                </div>
                <button type="submit" class="btn btn-outline-warning d-flex align-items-center gap-2">
                  <i class="icon-base ti tabler-flag"></i> Tandai (Flag)
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection
