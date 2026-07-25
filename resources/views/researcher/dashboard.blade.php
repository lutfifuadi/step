@extends('layouts.admin-layout')

@section('title', 'Dashboard Peneliti')

@section('content')
@php
  use Illuminate\Support\Str;
@endphp
<div class="container-fluid py-2">
  <!-- Welcome Header Peneliti -->
  <div class="card border-0 mb-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--teal-deep) 0%, var(--teal-mid) 100%); border-radius: 5px; box-shadow: var(--shadow-card);">
    <!-- Decorative Circle Blobs inside the card -->
    <div class="position-absolute" style="width: 250px; height: 250px; background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%); top: -70px; right: -50px; border-radius: 50%;"></div>
    <div class="position-absolute" style="width: 150px; height: 150px; background: radial-gradient(circle, var(--amber) 0%, transparent 70%); bottom: -40px; right: 100px; border-radius: 50%; opacity: 0.15;"></div>
    
    <div class="card-body p-4 p-md-5 position-relative">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <span class="badge bg-info text-dark mb-3 px-3 py-2 text-uppercase font-semibold tracking-wider" style="font-size: 0.75rem; border-radius: 30px;">Data & Ekspor Peneliti</span>
          <h3 class="fw-bold mb-2 text-warning">Selamat Datang Peneliti, Admin STEP!</h3>
          <p class="mb-0 text-white-50" style="font-size: 0.95rem; max-width: 650px;">
            Akses data ekspresi yang telah disetujui (dibersihkan) untuk kebutuhan riset dan analisis. Seluruh proses pengeksporan menerapkan penyamaran identitas (data masking) untuk melindungi data pribadi responden remaja secara ketat.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Baris Card Stats Kategori (.step-category-card) -->
  <div class="row g-4 mb-4">
    @foreach($stats as $category)
    <div class="col-md-3">
      <div class="step-category-card">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h6 class="text-teal-deep fw-bold mb-0" style="color: var(--teal-deep); font-size: 0.9rem;">{{ $category->name }}</h6>
          <span class="badge rounded-circle p-2" style="background-color: rgba(0, 105, 92, 0.1); color: var(--teal-mid);">
            <i class="icon-base ti tabler-folder fs-5"></i>
          </span>
        </div>
        <h3 class="fw-bold mb-1" style="color: var(--teal-deep); font-size: 1.8rem;">{{ $category->expressions_count }}</h3>
        <span class="text-muted small">Ekspresi Disetujui</span>
      </div>
    </div>
    @endforeach
  </div>

  <!-- Form Ekspor Data (Teal STEP Styling) & Progress Indicator -->
  <div class="row g-4 mb-4">
    <div class="col-12">
      <div class="card border-0" style="border-radius: 5px; box-shadow: var(--shadow-card); background-color: var(--white);">
        <div class="card-body p-4">
          <div class="d-flex align-items-start gap-2 mb-3">
            <span class="badge p-2 rounded-circle" style="background-color: rgba(0, 105, 92, 0.1); color: var(--teal-mid);">
              <i class="icon-base ti tabler-download fs-4"></i>
            </span>
            <div>
              <h5 class="fw-bold mb-1 text-teal-deep" style="color: var(--teal-deep);">Ekspor Data Aman (Secure Export)</h5>
              <p class="text-muted small mb-0">Tentukan kriteria pencarian dan ekspor ke format Excel. Proses ekspor berjalan di antrean latar belakang demi efisiensi.</p>
            </div>
          </div>
 
          <form id="exportForm" class="row g-3 align-items-end">
            @csrf
            <div class="col-md-4">
              <label class="form-label fw-semibold text-teal-deep" style="color: var(--teal-deep); font-size: 0.85rem;">Kategori</label>
              <select name="category_id" id="exportCategory" class="form-select border-teal-soft" style="border-radius: 5px;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold text-teal-deep" style="color: var(--teal-deep); font-size: 0.85rem;">Dari Tanggal</label>
              <input type="date" class="form-control border-teal-soft" name="from" id="exportFrom" style="border-radius: 5px;">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold text-teal-deep" style="color: var(--teal-deep); font-size: 0.85rem;">Sampai Tanggal</label>
              <input type="date" class="form-control border-teal-soft" name="to" id="exportTo" style="border-radius: 5px;">
            </div>
            <div class="col-md-2">
              <button type="submit" id="btnRequestExport" class="btn text-white w-100 font-semibold" style="border-radius: 5px; background-color: var(--teal-mid); border-color: var(--teal-mid); padding: 10px 15px;">
                Mulai Ekspor
              </button>
            </div>
          </form>
 
          <!-- Export Progress Indicator -->
          <div id="exportProgressContainer" class="mt-4 d-none">
            <div class="card border p-3" style="background-color: var(--cream); border-radius: 5px; border-color: var(--teal-soft) !important;">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <strong id="exportStatusText" class="text-teal-deep" style="color: var(--teal-deep);">Menyiapkan ekspor...</strong>
                <span id="exportSpinner" class="spinner-border spinner-border-sm" style="color: var(--teal-mid);" role="status"></span>
              </div>
              <div class="progress mb-2" style="height: 10px; border-radius: 5px; background-color: rgba(0, 105, 92, 0.1);">
                <div id="exportProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 10%; background-color: var(--teal-mid);"></div>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <small id="exportDetailsText" class="text-muted">Mohon tunggu sebentar.</small>
                <div id="exportDownloadWrapper"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
 
  <!-- Tabel Ekspresi Disesuaikan -->
  <div class="row g-4">
    <div class="col-12">
      <div class="card border-0" style="border-radius: 5px; box-shadow: var(--shadow-card); background-color: var(--white);">
        <div class="card-body p-4">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
              <h5 class="fw-bold mb-1 text-teal-deep" style="color: var(--teal-deep);">Daftar Ekspresi Disetujui</h5>
              <p class="text-muted small mb-0">Menampilkan data ekspresi dengan nama penyamaran (masked) yang siap dianalisis.</p>
            </div>
          </div>

          @if($expressions->count())
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr class="text-muted" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                  <th class="ps-0">ID</th>
                  <th>Kategori</th>
                  <th>Nama Tampil (Masked)</th>
                  <th>Isi Ekspresi</th>
                  <th>Status</th>
                  <th class="text-end pe-0">Tanggal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($expressions as $expression)
                <tr>
                  <td class="ps-0 fw-semibold text-muted">#{{ $expression->id }}</td>
                  <td>
                    <span class="badge bg-light text-dark border" style="border-radius: 6px;">{{ $expression->category?->name ?? '-' }}</span>
                  </td>
                  <td class="fw-semibold text-dark">{{ $expression->display_name }}</td>
                  <td class="text-muted text-wrap" style="max-width: 350px;">{{ Str::limit($expression->content, 120) }}</td>
                  <td>
                    <span class="badge text-white" style="background-color: var(--teal-mid); border-radius: 30px; font-size: 0.75rem; padding: 4px 10px;">
                      {{ ucfirst($expression->status) }}
                    </span>
                  </td>
                  <td class="text-end pe-0 text-muted small">{{ $expression->created_at?->format('d/m/Y') }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-4 gap-3">
            <div class="text-muted small">
              Menampilkan {{ $expressions->firstItem() ?? 0 }} s.d {{ $expressions->lastItem() ?? 0 }} dari {{ $expressions->total() }} data
            </div>
            <div>
              {{ $expressions->links('vendor.pagination.bootstrap-5') }}
            </div>
          </div>
          @else
          <div class="text-center py-5">
            <div class="mb-3 text-muted">
              <i class="icon-base ti tabler-mood-empty fs-1"></i>
            </div>
            <h6 class="fw-bold mb-1">Belum Ada Data</h6>
            <p class="text-muted small mb-0">Belum ada ekspresi yang berstatus disetujui saat ini.</p>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

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
        exportProgressBar.className = 'progress-bar progress-bar-striped progress-bar-animated';
        exportProgressBar.style.backgroundColor = 'var(--teal-mid)';
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
                    exportProgressBar.className = 'progress-bar';
                    exportProgressBar.style.backgroundColor = '#28a745'; // success green
                    exportSpinner.classList.add('d-none');
                    exportDetailsText.innerText = `Berhasil memproses ${data.row_count} baris data. File siap diunduh secara aman.`;
                    
                    exportDownloadWrapper.innerHTML = `
                        <a href="${data.download_url}" class="btn btn-sm text-white" style="background-color: #28a745; border-color: #28a745; border-radius: 5px;" target="_blank">
                            <i class="icon-base ti tabler-download me-1"></i> Unduh Excel
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
        exportProgressBar.className = 'progress-bar';
        exportProgressBar.style.backgroundColor = '#dc3545'; // danger red
        exportSpinner.classList.add('d-none');
        exportDetailsText.innerText = message;
        
        btnRequestExport.disabled = false;
        btnRequestExport.innerText = 'Mulai Ekspor';
    }
});
</script>
@endpush
@endsection
