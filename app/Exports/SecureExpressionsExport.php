<?php

namespace App\Exports;

use App\Models\Expression;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SecureExpressionsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private ?int $categoryId = null,
        private ?string $from = null,
        private ?string $to = null
    ) {}

    public function query()
    {
        // Query data ekspresi secara efisien menggunakan chunked/lazy loading di handle.
        // Maatwebsite Excel handles chunking internally when FromQuery is implemented.
        // Let's ensure query is clean, optimized, and does not load unused relationships except category.
        return Expression::query()
            ->approved()
            ->with('category')
            ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
            ->when($this->from, fn ($q) => $q->whereDate('created_at', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('created_at', '<=', $this->to))
            ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        // Hanya sertakan ID pseudonim, isi ekspresi, nama kategori, status, tanggal dikirim, dan flag risiko.
        return [
            'ID Pseudonim',
            'Isi Ekspresi',
            'Kategori',
            'Status',
            'Tanggal Dikirim',
            'Risiko Kebencanaan/Self-Harm (Flag Risiko)'
        ];
    }

    public function map($expression): array
    {
        // Data Masking secara ketat:
        // Nama asli (terenkripsi) TIDAK boleh didekripsi, diganti dengan pseudonim format "Responden-{hash8char}" atau "Responden-{random_id}"
        // Kita buat pseudonim unik berdasarkan expression ID dengan secure hashing.
        $hash8char = substr(hash('sha256', $expression->id . 'step_salt_secure_pseudonym'), 0, 8);
        $pseudonym = "Responden-{$hash8char}";

        return [
            $pseudonym,
            $expression->content,
            $expression->category?->name ?? '-',
            ucfirst($expression->status),
            $expression->created_at?->format('d/m/Y H:i:s') ?? '-',
            $expression->is_risky ? 'Berisiko' : 'Aman'
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
