@php
$pageConfigs = ['layout' => 'front'];
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Dashboard Peneliti')

@section('content')
<section class="container py-5">
  <div class="row">
    <div class="col-12 mb-4">
      <h2>Dashboard Peneliti</h2>
      <p class="text-muted">Data ekspresi yang telah disetujui dan filter ekspor untuk keperluan penelitian.</p>
    </div>

    <div class="col-12 mb-4">
      <div class="row g-3">
        @foreach($stats as $category)
        <div class="col-md-3">
          <div class="card p-3 shadow-sm">
            <h6>{{ $category->name }}</h6>
            <p class="display-6 mb-0">{{ $category->expressions_count }}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <div class="col-12 mb-4">
      <div class="card p-4 shadow-sm">
        <h5>Ekspor Data Aman</h5>
        <p class="text-sm text-muted">Menerapkan data masking secara ketat untuk melindungi responden remaja. Proses ekspor berjalan di latar belakang.</p>
        <form id="exportForm" class="row g-3 align-items-end">
          @csrf
          <div class="col-md-4">
            <label class="form-label">Kategori</label>
            <select name="category_id" id="exportCategory" class="form-select">
              <option value="">Semua</option>
              @foreach($categories as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Dari</label>
            <input type="date" class="form-control" name="from" id="exportFrom">
          </div>
          <div class="col-md-3">
            <label class="form-label">Sampai</label>
            <input type="date" class="form-control" name="to" id="exportTo">
          </div>
          <div class="col-md-2">
            <button type="submit" id="btnRequestExport" class="btn btn-primary w-100">Mulai Ekspor</button>
          </div>
        </form>

        <!-- Export Progress Indicator -->
        <div id="exportProgressContainer" class="mt-4 d-none">
          <div class="card bg-light border p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <strong id="exportStatusText">Menyiapkan ekspor...</strong>
              <span id="exportSpinner" class="spinner-border spinner-border-sm text-primary" role="status"></span>
            </div>
            <div class="progress mb-2" style="height: 10px;">
              <div id="exportProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 10%"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <small id="exportDetailsText" class="text-muted">Mohon tunggu sebentar.</small>
              <div id="exportDownloadWrapper"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card p-4 shadow-sm">
        <h5>Ekspresi Terbaru</h5>
        @if($expressions->count())
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Kategori</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($expressions as $expression)
              <tr>
                <td>{{ $expression->id }}</td>
                <td>{{ $expression->category?->name ?? '-' }}</td>
                <td>{{ $expression->display_name }}</td>
                <td>{{ ucfirst($expression->status) }}</td>
                <td>{{ $expression->created_at?->format('d/m/Y') }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $expressions->links() }}
        @else
        <div class="alert alert-secondary">Belum ada ekspresi yang disetujui.</div>
        @endif
      </div>
    </div>
  </div>
</section>
</section>

@push('pricing-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const exportForm = document.getElementById('exportForm');
    const btnRequestExport = document.getElementById('btnRequestExport');
    const exportProgressContainer = document.getElementById('exportProgressContainer');
    const exportStatusText = document.getElementById('exportStatusText');
    const exportDetailsText = document.getElementById('exportDetailsText');
    const exportProgressBar = document.getElementById('exportProgressBar');
    const exportSpinner = document.getElementById('exportSpinner');
    const exportDownloadWrapper = document.getElementById('exportDownloadWrapper');

    let pollInterval = null;

    exportForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // UI Feedback
        btnRequestExport.disabled = true;
        btnRequestExport.innerText = 'Memproses...';
        exportProgressContainer.classList.remove('d-none');
        exportStatusText.innerText = 'Membuat permintaan ekspor...';
        exportProgressBar.style.width = '15%';
        exportProgressBar.className = 'progress-bar progress-bar-striped progress-bar-animated bg-primary';
        exportSpinner.classList.remove('d-none');
        exportDownloadWrapper.innerHTML = '';
        exportDetailsText.innerText = 'Permintaan ekspor dikirim ke antrean sistem.';

        const categoryId = document.getElementById('exportCategory').value;
        const from = document.getElementById('exportFrom').value;
        const to = document.getElementById('exportTo').value;

        fetch('{{ route("researcher.export.request") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                category_id: categoryId,
                from: from,
                to: to
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                exportStatusText.innerText = 'Mengekspor data di antrean latar belakang...';
                exportProgressBar.style.width = '40%';
                exportDetailsText.innerText = 'Job ID: ' + data.export_log_id + '. Memulai polling status ekspor...';
                startPolling(data.export_log_id);
            } else {
                showError(data.message || 'Terjadi kesalahan sistem.');
            }
        })
        .catch(err => {
            console.error(err);
            showError('Gagal mengirimkan permintaan ekspor.');
        });
    });

    function startPolling(exportLogId) {
        if (pollInterval) clearInterval(pollInterval);

        pollInterval = setInterval(() => {
            fetch(`/researcher/export/status/${exportLogId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'processing') {
                    exportStatusText.innerText = 'Sedang memproses data...';
                    exportProgressBar.style.width = '70%';
                    exportDetailsText.innerText = 'Mengekstrak dan menerapkan data masking secara aman...';
                } else if (data.status === 'completed') {
                    clearInterval(pollInterval);
                    exportStatusText.innerText = 'Ekspor Selesai!';
                    exportProgressBar.style.width = '100%';
                    exportProgressBar.className = 'progress-bar bg-success';
                    exportSpinner.classList.add('d-none');
                    exportDetailsText.innerText = `Berhasil memproses ${data.row_count} baris data. File siap diunduh secara aman.`;
                    
                    exportDownloadWrapper.innerHTML = `
                        <a href="${data.download_url}" class="btn btn-sm btn-success" target="_blank">
                            <i class="bx bx-download me-1"></i> Unduh Excel
                        </a>
                    `;

                    // Reset submit button
                    btnRequestExport.disabled = false;
                    btnRequestExport.innerText = 'Mulai Ekspor';
                } else if (data.status === 'failed') {
                    clearInterval(pollInterval);
                    showError(data.error_message || 'Proses ekspor gagal.');
                }
            })
            .catch(err => {
                console.error(err);
            });
        }, 2000); // Poll every 2 seconds
    }

    function showError(message) {
        exportStatusText.innerText = 'Ekspor Gagal';
        exportProgressBar.style.width = '100%';
        exportProgressBar.className = 'progress-bar bg-danger';
        exportSpinner.classList.add('d-none');
        exportDetailsText.innerText = message;
        
        btnRequestExport.disabled = false;
        btnRequestExport.innerText = 'Mulai Ekspor';
    }
});
</script>
@endpush
@endsection
