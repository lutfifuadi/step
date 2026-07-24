@extends('layouts.admin-layout')

@section('title', 'Edit Konten Landing Page')

@section('page-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trumbowyg@2.26.0/dist/ui/trumbowyg.min.css">
<style>
  .trumbowyg-box {
    border-radius: 5px;
    border: 1px solid var(--teal-soft, #b2dfdb);
  }
  .btn-teal {
    background-color: var(--teal-mid, #00695c);
    border-color: var(--teal-mid, #00695c);
    color: #fff;
  }
  .btn-teal:hover, .btn-teal:focus, .btn-teal:active {
    background-color: var(--teal-deep, #003d33);
    border-color: var(--teal-deep, #003d33);
    color: #fff;
  }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-10">
    <div class="card shadow-sm" style="border-radius: 5px;">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <div>
          <h4 class="mb-0 fw-bold text-teal-deep">Edit Konten</h4>
          <p class="text-muted small mb-0">Ubah teks dinamis untuk section <strong>{{ ucwords(str_replace('_', ' ', $programContent->section)) }}</strong> (key: <code>{{ $programContent->key }}</code>)</p>
        </div>
        <a href="{{ route('admin.program-contents.index', ['section' => $programContent->section]) }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
          <i class="icon-base ti tabler-arrow-left"></i> <span>Kembali</span>
        </a>
      </div>

      <div class="card-body py-4">
        @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading fw-bold mb-1">Perbaiki kesalahan berikut:</h6>
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <form action="{{ route('admin.program-contents.update', $programContent) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="row g-3">
            <!-- Title -->
            <div class="col-12">
              <label for="title" class="form-label fw-semibold text-teal-deep">Judul / Eyebrow Text <span class="text-muted small font-normal">(Bisa berisi tag HTML span accent)</span></label>
              <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $programContent->title) }}" placeholder="Masukkan judul/eyebrow...">
              <div class="form-text">Contoh tag HTML accent: <code>Suara Remaja untuk &lt;span class="accent"&gt;Keterlibatan Ayah&lt;/span&gt; yang Lebih Bermakna</code></div>
            </div>

            <!-- Body (Rich Text / WYSIWYG) -->
            <div class="col-12">
              <label for="body" class="form-label fw-semibold text-teal-deep">Body Content / Teks Deskripsi</label>
              <textarea name="body" id="body" class="form-control editor" rows="6" placeholder="Masukkan konten teks...">{!! old('body', $programContent->body) !!}</textarea>
            </div>

            <!-- Icon / SVG Raw -->
            <div class="col-12">
              <label for="icon" class="form-label fw-semibold text-teal-deep">Icon (HTML/SVG Raw atau Teks Angka)</label>
              <textarea name="icon" id="icon" class="form-control font-monospace" rows="4" placeholder="Masukkan tag svg lengkap, atau teks label icon...">{!! old('icon', $programContent->icon) !!}</textarea>
              <div class="form-text">Jika berisi tag SVG, pastikan formatnya valid. Jika berupa angka (untuk nomor urut), masukkan angkanya saja (misal: <code>1</code>).</div>
            </div>

            <!-- Urutan & Status -->
            <div class="col-md-6">
              <label for="sort_order" class="form-label fw-semibold text-teal-deep">Urutan Tampil</label>
              <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $programContent->sort_order) }}" required min="0">
            </div>

            <div class="col-md-6 d-flex align-items-end">
              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $programContent->is_active) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-teal-deep" for="is_active">Aktifkan Konten ini di Halaman Publik</label>
              </div>
            </div>
          </div>

          <div class="step-form-actions justify-content-end">
            <a href="{{ route('admin.program-contents.index', ['section' => $programContent->section]) }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-teal d-flex align-items-center gap-1">
              <i class="icon-base ti tabler-device-floppy"></i> <span>Simpan Perubahan</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.26.0/dist/trumbowyg.min.js"></script>
<script>
  $(document).ready(function() {
    $('.editor').trumbowyg({
      btns: [
        ['viewHTML'],
        ['undo', 'redo'],
        ['formatting'],
        ['strong', 'em', 'del', 'underline'],
        ['link'],
        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        ['unorderedList', 'orderedList'],
        ['horizontalRule'],
        ['removeformat'],
        ['fullscreen']
      ]
    });
  });
</script>
@endsection
