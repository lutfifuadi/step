<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer', 'subject')->latest();

        // Filter: admin (causer)
        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id)
                  ->where('causer_type', User::class);
        }

        // Filter: jenis aksi (event / description)
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter: range tanggal
        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }

        // Filter: subject (morph type / subject ID)
        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Get activities with pagination (20 item)
        $activities = $query->paginate(20)->withQueryString();

        // Get admins list for filter dropdown
        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->get();

        // Get distinct event types
        $events = Activity::select('event')->whereNotNull('event')->distinct()->pluck('event');

        // Get distinct subject types
        $subjectTypes = Activity::select('subject_type')->whereNotNull('subject_type')->distinct()->pluck('subject_type');

        return view('admin.audit-log.index', compact('activities', 'admins', 'events', 'subjectTypes'));
    }
}
