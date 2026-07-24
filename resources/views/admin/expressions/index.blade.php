@extends('layouts.admin-layout')

@section('title', 'Daftar Ekspresi')

@section('content')
  @php
    $categories = \App\Models\Category::all();
  @endphp

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Card Heading: Judul, deskripsi, filter, dan aksi -->
  <div class="card shadow-sm mb-4" style="border-radius: 5px;">
    <div class="card-body">
      <div class="d-flex flex-column gap-3">
        <div>
          <h4 class="mb-1 fw-bold text-teal-deep">Daftar Ekspresi</h4>
          <p class="text-muted small mb-0">Kelola dan moderasi ekspresi atau curahan hati yang dikirimkan oleh pengguna secara aman.</p>
        </div>
        
        <!-- Form Filter Responsif -->
        <div class="bg-light p-3" style="border-radius: 5px;">
          <form action="{{ route('admin.expressions.index') }}" method="GET" class="row g-3 align-items-end">
            <!-- Search -->
            <div class="col-12 col-md-4">
              <label for="search" class="form-label fw-semibold text-teal-deep small">Kata Kunci</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border-radius: 5px 0 0 5px;"><i class="icon-base ti tabler-search text-muted"></i></span>
                <input type="text" name="search" id="search" class="form-control border-start-0" placeholder="Cari isi curahan..." value="{{ request('search') }}" style="border-radius: 0 5px 5px 0;">
              </div>
            </div>

            <!-- Kategori -->
            <div class="col-12 col-sm-6 col-md-3">
              <label for="category_id" class="form-label fw-semibold text-teal-deep small">Kategori</label>
              <select name="category_id" id="category_id" class="form-select" style="border-radius: 5px;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Status -->
            <div class="col-12 col-sm-6 col-md-3">
              <label for="status" class="form-label fw-semibold text-teal-deep small">Status</label>
              <select name="status" id="status" class="form-select" style="border-radius: 5px;">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Tertunda (Pending)</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                <option value="flagged" {{ request('status') == 'flagged' ? 'selected' : '' }}>Diflag (Flagged)</option>
              </select>
            </div>

            <!-- Buttons -->
            <div class="col-12 col-md-2 d-flex gap-2">
              <button type="submit" class="btn text-white w-100 d-flex align-items-center justify-content-center gap-1" style="background-color: var(--teal-mid); border-color: var(--teal-mid); border-radius: 5px;">
                <i class="icon-base ti tabler-filter fs-5"></i> Filter
              </button>
              <a href="{{ route('admin.expressions.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-1" style="border-radius: 5px;">
                <i class="icon-base ti tabler-refresh fs-5"></i> Reset
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Card Tabel Utama: Tabel data dan pagination -->
  <div class="card shadow-sm" style="border-radius: 5px;">
    <div class="card-body">
      @if($expressions->count() > 0)
        <div class="table-responsive text-nowrap">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th style="width: 80px; border-top-left-radius: 5px;">ID</th>
                <th>Kategori</th>
                <th>Nama Tampil</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th class="text-center" style="width: 120px; border-top-right-radius: 5px;">Tindakan</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @foreach($expressions as $expression)
                <tr>
                  <td><span class="fw-bold text-dark">#{{ $expression->id }}</span></td>
                  <td>
                    @if($expression->category)
                      <span class="badge bg-label-primary" style="border-radius: 3px;">{{ $expression->category->name }}</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    <span class="fw-semibold text-dark">{{ $expression->display_name }}</span>
                  </td>
                  <td>
                    @if($expression->status === 'approved')
                      <span class="badge bg-label-success" style="border-radius: 3px;">Disetujui</span>
                    @elseif($expression->status === 'flagged')
                      <span class="badge bg-label-danger" style="border-radius: 3px;">Diflag</span>
                    @else
                      <span class="badge bg-label-warning" style="border-radius: 3px;">Tertunda</span>
                    @endif
                  </td>
                  <td>
                    <span class="small text-muted">{{ $expression->created_at->format('d M Y H:i') }}</span>
                  </td>
                  <td class="text-center">
                    <a href="{{ route('admin.expressions.show', $expression) }}" class="btn btn-sm text-white" style="background-color: var(--teal-mid); border-color: var(--teal-mid); border-radius: 5px;">
                      <i class="icon-base ti tabler-eye me-1 small"></i> Detail
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Pagination Info & Links -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
          <div class="text-muted small">
            Menampilkan {{ $expressions->firstItem() ?? 0 }} s.d {{ $expressions->lastItem() ?? 0 }} dari {{ $expressions->total() }} data
          </div>
          <div>
            {{ $expressions->links() }}
          </div>
        </div>
      @else
        <!-- Empty State -->
        <div class="text-center py-5">
          <div class="mb-3">
            <i class="icon-base ti tabler-message-2-off text-muted" style="font-size: 3.5rem;"></i>
          </div>
          <h5 class="fw-bold text-teal-deep">Belum Ada Ekspresi</h5>
          <p class="text-muted small mx-auto" style="max-width: 350px;">
            Tidak ditemukan data ekspresi yang sesuai dengan filter pencarian saat ini atau database sedang kosong.
          </p>
        </div>
      @endif
    </div>
  </div>
@endsection
