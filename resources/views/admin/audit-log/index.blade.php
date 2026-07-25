@extends('layouts.admin-layout')

@section('title', 'Audit Log')

@section('content')
<div class="card shadow-sm mb-4" style="border-radius: 5px;">
    <div class="card-body">
        <div class="d-flex flex-column gap-3">
            <div>
                <h4 class="mb-1 fw-bold text-teal-deep">Filter Audit Log</h4>
                <p class="text-muted small mb-0">Lakukan penyaringan riwayat aktivitas log admin berdasarkan kriteria di bawah.</p>
            </div>
            
            <div class="bg-light p-3" style="border-radius: 5px;">
                <form method="GET" action="{{ route('admin.audit-log.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="causer_id" class="form-label fw-semibold text-teal-deep small">Causer (Admin)</label>
                            <select name="causer_id" id="causer_id" class="form-select" style="border-radius: 5px;">
                                <option value="">Semua Admin</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ request('causer_id') == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="event" class="form-label fw-semibold text-teal-deep small">Event</label>
                            <select name="event" id="event" class="form-select" style="border-radius: 5px;">
                                <option value="">Semua Event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                                        {{ $event }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="subject_type" class="form-label fw-semibold text-teal-deep small">Subject Type</label>
                            <select name="subject_type" id="subject_type" class="form-select" style="border-radius: 5px;">
                                <option value="">Semua Subject Type</option>
                                @foreach($subjectTypes as $sType)
                                    <option value="{{ $sType }}" {{ request('subject_type') == $sType ? 'selected' : '' }}>
                                        {{ $sType }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="subject_id" class="form-label fw-semibold text-teal-deep small">Subject ID</label>
                            <input type="text" name="subject_id" id="subject_id" class="form-control" value="{{ request('subject_id') }}" placeholder="Subject ID" style="border-radius: 5px;">
                        </div>
                        <div class="col-md-3">
                            <label for="date_start" class="form-label fw-semibold text-teal-deep small">Tanggal Mulai</label>
                            <input type="date" name="date_start" id="date_start" class="form-control" value="{{ request('date_start') }}" style="border-radius: 5px;">
                        </div>
                        <div class="col-md-3">
                            <label for="date_end" class="form-label fw-semibold text-teal-deep small">Tanggal Selesai</label>
                            <input type="date" name="date_end" id="date_end" class="form-control" value="{{ request('date_end') }}" style="border-radius: 5px;">
                        </div>
                        <div class="col-md-6 d-flex align-items-end gap-2">
                            <button type="submit" class="btn text-white w-100 d-flex align-items-center justify-content-center gap-1" style="background-color: var(--teal-mid); border-color: var(--teal-mid); border-radius: 5px;">
                                <i class="icon-base ti tabler-filter fs-5"></i> Filter
                            </button>
                            <a href="{{ route('admin.audit-log.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-1" style="border-radius: 5px;">
                                <i class="icon-base ti tabler-refresh fs-5"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm" style="border-radius: 5px;">
    <div class="card-body">
        <div class="d-flex flex-column gap-3 mb-3">
            <div>
                <h4 class="mb-1 fw-bold text-teal-deep">Daftar Aktivitas</h4>
                <p class="text-muted small mb-0">Daftar rekaman log audit keamanan dan riwayat aktivitas perubahan data.</p>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="border-top-left-radius: 5px;">Waktu</th>
                        <th>Causer</th>
                        <th>Event</th>
                        <th>Subject</th>
                        <th>Deskripsi</th>
                        <th class="pe-3 text-center" style="border-top-right-radius: 5px; width: 250px;">Properties</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($activities as $activity)
                        <tr>
                            <td class="ps-3">
                                <span class="text-dark fw-semibold d-block">{{ $activity->created_at->format('d M Y') }}</span>
                                <span class="text-muted small">{{ $activity->created_at->format('H:i:s') }}</span>
                            </td>
                            <td>
                                @if($activity->causer)
                                    <span class="fw-bold text-dark">{{ $activity->causer->name }}</span>
                                    <span class="text-muted d-block small">ID: #{{ $activity->causer_id }}</span>
                                @else
                                    <span class="badge bg-label-secondary" style="border-radius: 3px;">System</span>
                                @endif
                            </td>
                            <td>
                                @if($activity->event == 'created')
                                    <span class="badge bg-label-success" style="border-radius: 3px;">created</span>
                                @elseif($activity->event == 'updated')
                                    <span class="badge bg-label-warning" style="border-radius: 3px;">updated</span>
                                @elseif($activity->event == 'deleted')
                                    <span class="badge bg-label-danger" style="border-radius: 3px;">deleted</span>
                                @else
                                    <span class="badge bg-label-info" style="border-radius: 3px;">{{ $activity->event ?? 'N/A' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($activity->subject_type)
                                    <span class="fw-semibold text-dark d-block">{{ class_basename($activity->subject_type) }}</span>
                                    <span class="text-muted small">ID: #{{ $activity->subject_id }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-wrap d-inline-block" style="min-width: 150px; max-width: 300px;">
                                    {{ $activity->description }}
                                </span>
                            </td>
                            <td class="pe-3">
                                @if($activity->properties && count($activity->properties) > 0)
                                    <pre class="step-audit-pre m-0" style="border-radius: 5px;">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4 ps-3 pe-3">Tidak ada log aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mt-4">
            <div class="text-muted small">
                Menampilkan {{ $activities->firstItem() ?? 0 }} sampai {{ $activities->lastItem() ?? 0 }} dari {{ $activities->total() }} data
            </div>
            <div>
                {{ $activities->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
