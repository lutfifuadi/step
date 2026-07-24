<?php

namespace App\Http\Controllers;

use App\Jobs\ExportExpressionsJob;
use App\Models\ExportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class ExportController extends Controller
{
    /**
     * Request a new export.
     */
    public function requestExport(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $filterParams = [
            'category_id' => $request->category_id,
            'from' => $request->from,
            'to' => $request->to,
        ];

        // Create export log
        $exportLog = ExportLog::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'filter_params' => $filterParams,
            'requested_at' => now(),
        ]);

        // Dispatch background job
        ExportExpressionsJob::dispatch($exportLog->id);

        // Audit Trail
        activity()
            ->performedOn($exportLog)
            ->causedBy(auth()->user())
            ->withProperties(['filter_params' => $filterParams])
            ->log('researcher_export_requested');

        return response()->json([
            'success' => true,
            'message' => 'Permintaan ekspor sedang diproses di background.',
            'export_log_id' => $exportLog->id,
        ]);
    }

    /**
     * Check status of export job (Polling API).
     */
    public function checkStatus(Request $request, $id)
    {
        $exportLog = ExportLog::where('user_id', auth()->id())
            ->findOrFail($id);

        $downloadUrl = null;
        if ($exportLog->status === 'completed' && $exportLog->file_path && Storage::disk('local')->exists($exportLog->file_path)) {
            // Generate temporary signed URL (valid for max 1 hour)
            $downloadUrl = URL::temporarySignedRoute(
                'researcher.export.download',
                now()->addMinutes(60),
                ['id' => $exportLog->id]
            );
        }

        return response()->json([
            'id' => $exportLog->id,
            'status' => $exportLog->status,
            'row_count' => $exportLog->row_count,
            'completed_at' => $exportLog->completed_at?->toIso8601String(),
            'expires_at' => $exportLog->expires_at?->toIso8601String(),
            'error_message' => $exportLog->status === 'failed' ? 'Terjadi kesalahan saat memproses ekspor.' : null,
            'download_url' => $downloadUrl,
        ]);
    }

    /**
     * Download the exported Excel file.
     * Protected by signed URL and user-ownership verification.
     */
    public function download(Request $request, $id)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Tautan unduhan tidak sah atau telah kedaluwarsa.');
        }

        $exportLog = ExportLog::findOrFail($id);

        // Pastikan download dikunci hanya untuk user yang meminta (user_id yang sama dengan export_logs.user_id)
        if ($exportLog->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki wewenang untuk mengunduh berkas ini.');
        }

        if ($exportLog->status === 'expired' || ($exportLog->expires_at && $exportLog->expires_at->isPast())) {
            abort(410, 'Berkas ekspor telah kedaluwarsa.');
        }

        if (!$exportLog->file_path || !Storage::disk('local')->exists($exportLog->file_path)) {
            abort(404, 'Berkas ekspor tidak ditemukan.');
        }

        // Audit Trail
        activity()
            ->performedOn($exportLog)
            ->causedBy(auth()->user())
            ->log('researcher_export_downloaded');

        // Download securely from private path
        $filename = 'STEP_Ekspresi_Aman_' . ($exportLog->completed_at ? $exportLog->completed_at->format('Ymd_His') : now()->format('Ymd_His')) . '.xlsx';
        return Storage::disk('local')->download($exportLog->file_path, $filename);
    }
}
