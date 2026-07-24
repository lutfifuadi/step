@extends('layouts.admin-layout')

@section('title', 'Manajemen Kontak Konselor')

@section('content')
  <!-- Card Heading: Judul, deskripsi, filter, dan aksi -->
  <div class="card shadow-sm mb-4" style="border-radius: 5px;">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-1 fw-bold text-teal-deep">Daftar Kontak Konselor</h4>
          <p class="text-muted small mb-0">Kelola informasi kontak konselor/BK sekolah yang akan ditampilkan di halaman publik secara dinamis.</p>
        </div>
        <a href="{{ route('admin.konselor.create') }}" class="btn d-flex align-items-center gap-2" style="background-color: var(--teal-mid); border-color: var(--teal-mid); color: white; border-radius: 5px;">
          <i class="icon-base ti tabler-plus"></i> Tambah Konselor
        </a>
      </div>
    </div>
  </div>

  <!-- Card Tabel Utama: Tabel data dan pagination -->
  <div class="card shadow-sm" style="border-radius: 5px;">
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 5px;">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

    @if($contacts->count() > 0)
      <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle">
          <thead class="table-light" style="border-radius: 5px;">
            <tr>
              <th style="border-top-left-radius: 5px;">Urutan</th>
              <th>Nama Konselor</th>
              <th>Jabatan & Institusi</th>
              <th>Telepon & Email</th>
              <th>Ruangan & Jam Layanan</th>
              <th>Status</th>
              <th class="text-center" style="border-top-right-radius: 5px;">Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach($contacts as $contact)
              <tr>
                <td>
                  <span class="badge bg-label-secondary fw-semibold" style="border-radius: 3px;">{{ $contact->sort_order }}</span>
                </td>
                <td>
                  <span class="fw-bold text-dark d-block">{{ $contact->name }}</span>
                </td>
                <td>
                  <span class="d-block text-dark fw-semibold small">{{ $contact->role }}</span>
                  <span class="text-muted small">{{ $contact->institusi }}</span>
                </td>
                <td>
                  <span class="d-block fw-semibold small" style="color: var(--teal-mid);">
                    <i class="icon-base ti tabler-phone me-1 small"></i>{{ $contact->phone }}
                  </span>
                  @if($contact->email)
                    <span class="text-muted small">
                      <i class="icon-base ti tabler-mail me-1 small"></i>{{ $contact->email }}
                    </span>
                  @else
                    <span class="text-muted small fst-italic">-</span>
                  @endif
                </td>
                <td>
                  @if($contact->room)
                    <span class="d-block text-dark small"><i class="icon-base ti tabler-map-pin me-1 small text-secondary"></i>{{ $contact->room }}</span>
                  @endif
                  @if($contact->availability)
                    <span class="text-muted small"><i class="icon-base ti tabler-clock me-1 small text-secondary"></i>{{ $contact->availability }}</span>
                  @endif
                </td>
                <td>
                  @if($contact->is_active)
                    <span class="badge bg-label-success" style="border-radius: 3px;">Aktif</span>
                  @else
                    <span class="badge bg-label-danger" style="border-radius: 3px;">Nonaktif</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex justify-content-center gap-2">
                    <form action="{{ route('admin.konselor.toggle', $contact) }}" method="POST" class="m-0">
                      @csrf
                      <button type="submit" class="btn btn-xs {{ $contact->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" style="border-radius: 5px;" title="{{ $contact->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                        <i class="icon-base ti {{ $contact->is_active ? 'tabler-eye-off' : 'tabler-eye' }} me-1"></i>
                        {{ $contact->is_active ? 'Nonaktif' : 'Aktifkan' }}
                      </button>
                    </form>

                    <a href="{{ route('admin.konselor.edit', $contact) }}" class="btn btn-xs btn-outline-primary" style="border-radius: 5px;" title="Edit Data">
                      <i class="icon-base ti tabler-edit me-1"></i> Edit
                    </a>

                    <form action="{{ route('admin.konselor.destroy', $contact) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kontak {{ $contact->name }} secara permanen?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-xs btn-outline-danger" style="border-radius: 5px;" title="Hapus Permanen">
                        <i class="icon-base ti tabler-trash me-1"></i> Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
          Menampilkan {{ $contacts->firstItem() ?? 0 }} sampai {{ $contacts->lastItem() ?? 0 }} dari {{ $contacts->total() }} data
        </div>
        <div>
          {{ $contacts->links() }}
        </div>
      </div>
    @else
      <div class="text-center py-5">
        <div class="mb-3">
          <i class="icon-base ti tabler-phone-off display-3 text-teal-deep" style="opacity: 0.4;"></i>
        </div>
        <h5 class="fw-bold text-teal-deep">Belum Ada Kontak Konselor</h5>
        <p class="text-muted mb-4 mx-auto" style="max-width: 450px;">Silakan tambahkan kontak konselor pertama untuk menampilkan data di halaman Ruang Ekspresi secara dinamis.</p>
        <a href="{{ route('admin.konselor.create') }}" class="btn text-white px-4" style="background-color: var(--teal-mid); border-color: var(--teal-mid); border-radius: 5px;">
          <i class="icon-base ti tabler-plus me-1"></i> Tambah Konselor
        </a>
      </div>
    @endif
  </div>
</div>
@endsection
