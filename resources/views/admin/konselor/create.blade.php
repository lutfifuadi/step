@extends('layouts.admin-layout')

@section('title', 'Tambah Kontak Konselor')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8 col-md-10 col-12">
    <div class="card shadow-sm" style="border-radius: 5px;">
      <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between" style="border-top-left-radius: 5px; border-top-right-radius: 5px;">
        <div>
          <h4 class="mb-0 fw-bold text-teal-deep">Tambah Kontak Konselor Baru</h4>
          <p class="text-muted small mb-0">Isi formulir berikut dengan informasi yang akurat untuk menambahkan konselor/BK baru.</p>
        </div>
        <a href="{{ route('admin.konselor.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 5px;">
          <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
        </a>
      </div>

      <div class="card-body py-4">
        @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 5px;">
            <h6 class="alert-heading fw-bold mb-1"><i class="icon-base ti tabler-alert-triangle me-1"></i> Periksa Inputan Anda:</h6>
            <ul class="mb-0 small">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <form action="{{ route('admin.konselor.store') }}" method="POST">
          @csrf

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold" for="name">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Dra. Hj. Siti Aminah, M.Pd." value="{{ old('name') }}" style="border-radius: 5px;" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold" for="role">Jabatan / Posisi <span class="text-danger">*</span></label>
              <input type="text" name="role" id="role" class="form-control @error('role') is-invalid @enderror" placeholder="Contoh: Koordinator BK / Konselor Utama" value="{{ old('role') }}" style="border-radius: 5px;" required>
              @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold" for="institusi">Institusi / Lembaga <span class="text-danger">*</span></label>
              <input type="text" name="institusi" id="institusi" class="form-control @error('institusi') is-invalid @enderror" placeholder="Contoh: MAN 1 Kota Bandung" value="{{ old('institusi', 'MAN 1 Kota Bandung') }}" style="border-radius: 5px;" required>
              @error('institusi')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold" for="phone">Nomor Telepon (WhatsApp) <span class="text-danger">*</span></label>
              <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Contoh: 081234567890 atau +6281234567890" value="{{ old('phone') }}" style="border-radius: 5px;" required>
              <small class="text-muted">Gunakan format Indonesia yang valid (+62 atau 0 diikuti 9-12 digit).</small>
              @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold" for="email">Alamat Email (Opsional)</label>
              <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Contoh: konselor@sekolah.sch.id" value="{{ old('email') }}" style="border-radius: 5px;">
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold" for="room">Ruang Kerja / Lokasi (Opsional)</label>
              <input type="text" name="room" id="room" class="form-control @error('room') is-invalid @enderror" placeholder="Contoh: Ruang BK Gedung A Lantai 1" value="{{ old('room') }}" style="border-radius: 5px;">
              @error('room')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold" for="availability">Jam Layanan / Ketersediaan (Opsional)</label>
              <input type="text" name="availability" id="availability" class="form-control @error('availability') is-invalid @enderror" placeholder="Contoh: Senin - Jumat, 07:00 - 15:00" value="{{ old('availability') }}" style="border-radius: 5px;">
              @error('availability')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold" for="sort_order">Urutan Tampil <span class="text-danger">*</span></label>
              <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" placeholder="Contoh: 1" value="{{ old('sort_order', 0) }}" min="0" style="border-radius: 5px;" required>
              <small class="text-muted">Angka lebih kecil akan ditampilkan lebih dahulu/atas.</small>
              @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 border-top pt-3 mt-3">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="is_active">Aktifkan kontak ini segera</label>
              </div>
              <small class="text-muted d-block">Jika dinonaktifkan, kontak ini tidak akan muncul di halaman publik.</small>
            </div>

            <div class="col-12 step-form-actions mt-4">
              <button type="submit" class="btn text-white px-4 py-2 me-2" style="background-color: var(--teal-mid); border-color: var(--teal-mid); border-radius: 5px;">
                <i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Kontak
              </button>
              <a href="{{ route('admin.konselor.index') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 5px;">
                Batal
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
