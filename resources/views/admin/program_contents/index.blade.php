@extends('layouts.admin-layout')

@section('title', 'CMS Konten Landing Page')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom">
    <div>
      <h4 class="mb-0 fw-bold text-teal-deep">CMS Konten Landing Page</h4>
      <p class="text-muted small mb-0">Kelola dan update teks, judul, body, dan ikon di halaman beranda, tentang, edukasi, dan pencegahan secara dinamis.</p>
    </div>
    <form action="{{ route('admin.program-contents.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center m-0">
      <div class="input-group input-group-merge" style="width: 250px;">
        <span class="input-group-text"><i class="icon-base ti tabler-search"></i></span>
        <input type="text" name="search" class="form-control" placeholder="Cari konten..." value="{{ request('search') }}">
      </div>

      <select name="section" class="form-select" style="width: 200px;" onchange="this.form.submit()">
        <option value="">Semua Section</option>
        @foreach($sections as $sec)
          <option value="{{ $sec }}" {{ request('section') === $sec ? 'selected' : '' }}>
            {{ ucwords(str_replace('_', ' ', $sec)) }}
          </option>
        @endforeach
      </select>

      @if(request('search') || request('section'))
        <a href="{{ route('admin.program-contents.index') }}" class="btn btn-outline-secondary">Reset</a>
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
              <th style="width: 15%;">Section & Key</th>
              <th style="width: 25%;">Title / Eyebrow</th>
              <th style="width: 35%;">Body Text</th>
              <th style="width: 10%;">Urutan</th>
              <th style="width: 10%;">Status</th>
              <th style="width: 5%;" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach($contents as $content)
              <tr>
                <td>
                  <span class="badge bg-label-info fw-semibold d-block text-start mb-1">{{ ucwords(str_replace('_', ' ', $content->section)) }}</span>
                  <code class="small text-muted">{{ $content->key }}</code>
                </td>
                <td>
                  @if($content->title)
                    <span class="text-dark fw-semibold d-block text-wrap" style="max-width: 280px;">{!! strip_tags($content->title, '<span><strong><em><b><i><br>') !!}</span>
                  @else
                    <span class="text-muted fst-italic small">Tidak ada judul</span>
                  @endif
                </td>
                <td>
                  @if($content->body)
                    <div class="text-muted text-wrap small" style="max-height: 80px; overflow-y: auto; max-width: 400px;">
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
                    <a href="{{ route('admin.program-contents.edit', $content) }}" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" title="Edit Konten">
                      <i class="icon-base ti tabler-edit me-1"></i> Edit
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center mt-4">
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
          <i class="icon-base ti tabler-file-off display-3"></i>
        </div>
        <h5 class="fw-bold">Tidak Ada Konten Ditemukan</h5>
        <p class="text-muted">Coba bersihkan pencarian atau filter yang Anda gunakan.</p>
      </div>
    @endif
  </div>
</div>
@endsection
