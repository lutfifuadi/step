@extends('layouts.admin-layout')

@section('title', 'Audit Log')

@section('content')
<div class="card shadow-sm mb-4" style="border-radius: 5px;">
    <div class="card-header bg-white py-3 border-bottom" style="border-top-left-radius: 5px; border-top-right-radius: 5px;">
        <h4 class="mb-0 fw-bold text-teal-deep">Filter Audit Log</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.audit-log.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="causer_id" class="form-label fw-semibold text-secondary">Causer (Admin)</label>
                    <select name="causer_id" id="causer_id" class="form-select">
                        <option value="">Semua Admin</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ request('causer_id') == $admin->id ? 'selected' : '' }}>
                                {{ $admin->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="event" class="form-label fw-semibold text-secondary">Event</label>
                    <select name="event" id="event" class="form-select">
                        <option value="">Semua Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                                {{ $event }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="subject_type" class="form-label fw-semibold text-secondary">Subject Type</label>
                    <select name="subject_type" id="subject_type" class="form-select">
                        <option value="">Semua Subject Type</option>
                        @foreach($subjectTypes as $sType)
                            <option value="{{ $sType }}" {{ request('subject_type') == $sType ? 'selected' : '' }}>
                                {{ $sType }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="subject_id" class="form-label fw-semibold text-secondary">Subject ID</label>
                    <input type="text" name="subject_id" id="subject_id" class="form-control" value="{{ request('subject_id') }}" placeholder="Subject ID">
                </div>
                <div class="col-md-3">
                    <label for="date_start" class="form-label fw-semibold text-secondary">Tanggal Mulai</label>
                    <input type="date" name="date_start" id="date_start" class="form-control" value="{{ request('date_start') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_end" class="form-label fw-semibold text-secondary">Tanggal Selesai</label>
                    <input type="date" name="date_end" id="date_end" class="form-control" value="{{ request('date_end') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn text-white w-100" style="background-color: var(--teal-mid); border-color: var(--teal-mid);">
                        <i class="icon-base ti tabler-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.audit-log.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="icon-base ti tabler-refresh me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm" style="border-radius: 5px;">
    <div class="card-header bg-white py-3 border-bottom" style="border-top-left-radius: 5px; border-top-right-radius: 5px;">
        <h4 class="mb-0 fw-bold text-teal-deep">Daftar Aktivitas</h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light" style="border-radius: 5px;">
                    <tr>
                        <th class="ps-4" style="border-top-left-radius: 5px;">Waktu</th>
                        <th>Causer</th>
                        <th>Event</th>
                        <th>Subject</th>
                        <th>Deskripsi</th>
                        <th class="pe-4" style="border-top-right-radius: 5px;">Properties</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($activities as $activity)
                        <tr>
                            <td class="ps-4">
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
                            <td class="pe-4">
                                @if($activity->properties && count($activity->properties) > 0)
                                    <pre class="step-audit-pre m-0">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4 ps-4 pe-4">Tidak ada log aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-body d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 border-top">
        <div class="text-muted small">
            Menampilkan {{ $activities->firstItem() ?? 0 }} sampai {{ $activities->lastItem() ?? 0 }} dari {{ $activities->total() }} data
        </div>
        <div class="m-0">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection
