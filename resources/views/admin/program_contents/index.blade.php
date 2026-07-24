@extends('layouts.admin-layout')

@section('title', 'CMS Konten Landing Page')

@section('page-style')
<style>
  .btn-outline-teal {
    color: var(--teal-mid);
    border-color: var(--teal-mid);
    background-color: transparent;
  }
  .btn-outline-teal:hover, .btn-outline-teal:focus, .btn-outline-teal:active {
    color: #fff;
    background-color: var(--teal-mid);
    border-color: var(--teal-mid);
  }
</style>
@endsection

@section('content')
<div class="card shadow-sm" style="border-radius: 5px;">
  <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom">
    <div>
      <h4 class="mb-0 fw-bold text-teal-deep">CMS Konten Landing Page</h4>
      <p class="text-muted small mb-0">Kelola dan update teks, judul, body, dan ikon di halaman beranda, tentang, edukasi, dan pencegahan secara dinamis.</p>
    </div>
    
    <form action="{{ route('admin.program-contents.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2 align-items-stretch align-items-sm-center m-0">
      <div class="input-group input-group-merge" style="min-width: 220px;">
        <span class="input-group-text"><i class="icon-base ti tabler-search text-muted"></i></span>
        <input type="text" name="search" class="form-control" placeholder="Cari konten..." value="{{ request('search') }}">
      </div>

      <select name="section" class="form-select" style="min-width: 180px;" onchange="this.form.submit()">
        <option value="">Semua Section</option>
        @foreach($sections as $sec)
          <option value="{{ $sec }}" {{ request('section') === $sec ? 'selected' : '' }}>
            {{ ucwords(str_replace('_', ' ', $sec)) }}
          </option>
        @endforeach
      </select>

      @if(request('search') || request('section'))
        <a href="{{ route('admin.program-contents.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-1">
          <i class="icon-base ti tabler-rotate-clockwise"></i> <span>Reset</span>
        </a>
      @endif
    </form>
  </div>

  <div class="card-body">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if($contents->count() > 0)
      <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Section</th>
              <th>Key</th>
              <th>Title</th>
              <th>Preview Body</th>
              <th>Urutan</th>
              <th>Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach($contents as $content)
              <tr>
                <td>
                  <span class="badge bg-label-info fw-semibold">{{ ucwords(str_replace('_', ' ', $content->section)) }}</span>
                </td>
                <td>
                  <code class="small text-muted">{{ $content->key }}</code>
                </td>
                <td>
                  @if($content->title)
                    <div class="text-dark fw-semibold text-wrap text-truncate" style="max-width: 250px;">
                      {!! strip_tags($content->title, '<span><strong><em><b><i><br>') !!}
                    </div>
                  @else
                    <span class="text-muted fst-italic small">Tidak ada judul</span>
                  @endif
                </td>
                <td>
                  @if($content->body)
                    <div class="text-muted text-wrap text-truncate small" style="max-width: 350px;">
                      {!! strip_tags($content->body, '<p><b><strong><i><em><u><a><ul><ol><li><br><span>') !!}
                    </div>
                  @else
                    <span class="text-muted fst-italic small">Tidak ada konten deskripsi</span>
                  @endif
                </td>
                <td>
                  <span class="badge bg-label-secondary fw-semibold">{{ $content->sort_order }}</span>
                </td>
                <td>
                  @if($content->is_active)
                    <span class="badge bg-label-success">Aktif</span>
                  @else
                    <span class="badge bg-label-danger">Nonaktif</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex justify-content-center">
                    <a href="{{ route('admin.program-contents.edit', $content) }}" class="btn btn-sm btn-outline-teal d-flex align-items-center gap-1" title="Edit Konten">
                      <i class="icon-base ti tabler-edit"></i> <span>Edit</span>
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      
      <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-4 gap-3">
        <div class="text-muted small">
          Menampilkan {{ $contents->firstItem() ?? 0 }} sampai {{ $contents->lastItem() ?? 0 }} dari {{ $contents->total() }} data
        </div>
        <div>
          {{ $contents->links() }}
        </div>
      </div>
    @else
      <div class="text-center py-5">
        <div class="mb-3 text-muted">
          <i class="icon-base ti tabler-file-off display-3 text-teal-soft"></i>
        </div>
        <h5 class="fw-bold text-teal-deep">Tidak Ada Konten Ditemukan</h5>
        <p class="text-muted">Coba bersihkan pencarian atau filter yang Anda gunakan.</p>
        @if(request('search') || request('section'))
          <a href="{{ route('admin.program-contents.index') }}" class="btn btn-sm btn-outline-teal mt-2">
            <i class="icon-base ti tabler-rotate-clockwise me-1"></i> Bersihkan Filter
          </a>
        @endif
      </div>
    @endif
  </div>
</div>
@endsection
