@extends('layouts/contentNavbarLayout')

@section('title', 'Audit Log')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Admin /</span> Audit Log
</h4>

<div class="card mb-4">
    <h5 class="card-header">Filter Audit Log</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.audit-log.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="causer_id" class="form-label">Causer (Admin)</label>
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
                    <label for="event" class="form-label">Event</label>
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
                    <label for="subject_type" class="form-label">Subject Type</label>
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
                    <label for="subject_id" class="form-label">Subject ID</label>
                    <input type="text" name="subject_id" id="subject_id" class="form-control" value="{{ request('subject_id') }}" placeholder="Subject ID">
                </div>
                <div class="col-md-3">
                    <label for="date_start" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="date_start" id="date_start" class="form-control" value="{{ request('date_start') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_end" class="form-label">Tanggal Selesai</label>
                    <input type="date" name="date_end" id="date_end" class="form-control" value="{{ request('date_end') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('admin.audit-log.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <h5 class="card-header">Daftar Aktivitas</h5>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Causer</th>
                    <th>Event</th>
                    <th>Subject</th>
                    <th>Deskripsi</th>
                    <th>Properties</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($activities as $activity)
                    <tr>
                        <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            @if($activity->causer)
                                {{ $activity->causer->name }}
                            @else
                                <span class="text-muted">System</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-label-info">{{ $activity->event ?? 'N/A' }}</span>
                        </td>
                        <td>
                            @if($activity->subject_type)
                                <small class="text-muted">{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $activity->description }}</td>
                        <td>
                            @if($activity->properties && count($activity->properties) > 0)
                                <pre class="m-0" style="font-size: 11px;">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada log aktivitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex justify-content-end">
        {{ $activities->links() }}
    </div>
</div>
@endsection
