<?php

namespace App\Jobs;

use App\Models\ExportLog;
use App\Models\Expression;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExpressionsExport;

class ExportExpressionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $exportLogId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $exportLog = ExportLog::find($this->exportLogId);

        if (!$exportLog) {
            return;
        }

        try {
            $exportLog->update([
                'status' => 'processing',
            ]);

            $filterParams = $exportLog->filter_params ?? [];
            $categoryId = $filterParams['category_id'] ?? null;
            $from = $filterParams['from'] ?? null;
            $to = $filterParams['to'] ?? null;

            // Generate UUID filename
            $uuid = (string) Str::uuid();
            $fileName = "exports/{$uuid}.xlsx";

            // Count rows first (using same query criteria as export but count)
            $query = Expression::query()
                ->approved() // only approved expression per requirement/original export code
                ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
                ->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
                ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to));

            $totalRows = $query->count();

            // Export to temporary file using private storage
            // Maatwebsite Excel Excel::store() saves to default disk
            // We want private disk or local disk private path: storage/app/private/exports/{uuid}.xlsx
            // Laravel 11/12 private disk/storage path: storage/app/private/ is the local disk's default for private files
            // Let's store using storage facade or relative path.
            // Let's use 'local' disk which writes to storage/app, so exports/{uuid}.xlsx goes to storage/app/exports/{uuid}.xlsx.
            // But wait, the PRD says: "local disk private path: `storage/app/private/exports/{uuid}.xlsx`".
            // In Laravel 11+, the storage directory structure is storage/app/private. Let's make sure it's stored exactly there.
            // In Laravel 11+, local disk can be configured or we can use custom disk path or create it directly.
            // Let's register or write using Excel::store with 'local' disk, and save to 'private/exports/' . $uuid . '.xlsx' 
            // because 'local' disk root is storage/app, so 'private/exports/{uuid}.xlsx' will resolve to 'storage/app/private/exports/{uuid}.xlsx'.
            $relativeFilePath = "private/exports/{$uuid}.xlsx";

            // We must mask data: Nama asli (terenkripsi) TIDAK boleh didekripsi, diganti dengan pseudonim format "Responden-{hash8char}" atau "Responden-{random_id}". Hapus kolom IP address, email, dan catatan internal moderasi. Hanya sertakan ID pseudonim, isi ekspresi, nama kategori, status, tanggal dikirim, dan flag risiko.
            // Let's check what Columns are expected to be included: "Hanya sertakan ID pseudonim, isi ekspresi, nama kategori, status, tanggal dikirim, dan flag risiko."
            // ID pseudonim: format "Responden-{hash8char}" or "Responden-{random_id}" di mana hash8char bisa di-generate dari user_id atau expression id, atau acak. Mari kita generate dengan hash id. md5(expression_id) truncated to 8 chars or CRC32/SHA256, e.g., substr(md5($expression->id), 0, 8) or similar. Let's use "Responden-" . substr(md5($expression->id . 'salt_secure_pseudonym'), 0, 8).
            // Let's create a custom Export class or instantiate ExpressionsExport with a secure mapping.
            // Let's look at ExpressionsExport to adapt it, or write a dedicated export inside or refactor ExpressionsExport.
            // Let's write a secured/anonymous export class or update ExpressionsExport.
            // Let's read ExpressionsExport again. It has ID, Tanggal, Kategori, Nama Tampil, Asal, Isi Ekspresi, Status, Anonim.
            // The requirement says:
            // "Menerapkan Data Masking secara ketat: Nama asli (terenkripsi) TIDAK boleh didekripsi, diganti dengan pseudonim format "Responden-{hash8char}" atau "Responden-{random_id}". Hapus kolom IP address, email, dan catatan internal moderasi. Hanya sertakan ID pseudonim, isi ekspresi, nama kategori, status, tanggal dikirim, dan flag risiko."
            //
            // Let's create a new export class specifically for this researcher export: App\Exports\SecureExpressionsExport.php
            // Or we can modify/replace ExpressionsExport. Let's create SecureExpressionsExport to be safe and precise.

            Excel::store(new \App\Exports\SecureExpressionsExport($categoryId, $from, $to), $relativeFilePath, 'local');

            $exportLog->update([
                'status' => 'completed',
                'file_path' => $relativeFilePath,
                'row_count' => $totalRows,
                'completed_at' => now(),
                'expires_at' => now()->addHours(24),
            ]);

            // Log activity
            activity()
                ->performedOn($exportLog)
                ->causedBy($exportLog->user)
                ->withProperties(['filter_params' => $filterParams, 'row_count' => $totalRows])
                ->log('researcher_export_completed');

        } catch (\Exception $e) {
            $exportLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage() . "\n" . $e->getTraceAsString(),
                'completed_at' => now(),
            ]);

            activity()
                ->performedOn($exportLog)
                ->causedBy($exportLog->user)
                ->withProperties(['error' => $e->getMessage()])
                ->log('researcher_export_failed');
        }
    }
}
