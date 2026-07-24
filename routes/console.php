<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\ExportLog;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Scheduler task to delete export files on local disk
 * and update status to 'expired' if they are older than 24 hours.
 */
\Illuminate\Support\Facades\Schedule::call(function () {
    $expiredLogs = ExportLog::where('status', '!=', 'expired')
        ->where('created_at', '<', now()->subHours(24))
        ->get();

    foreach ($expiredLogs as $log) {
        if ($log->file_path && Storage::disk('local')->exists($log->file_path)) {
            Storage::disk('local')->delete($log->file_path);
        }

        $log->update([
            'status' => 'expired',
        ]);
    }
})->daily()->name('clean-expired-exports');

