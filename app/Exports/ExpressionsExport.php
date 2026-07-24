<?php

namespace App\Exports;

use App\Models\Expression;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpressionsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private ?int $categoryId = null, private ?string $status = 'approved', private ?string $from = null, private ?string $to = null)
    {
    }

    public function query()
    {
        return Expression::query()
            ->with('category')
            ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->from, fn ($q) => $q->whereDate('created_at', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('created_at', '<=', $this->to))
            ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return ['ID', 'Tanggal', 'Kategori', 'Nama Tampil', 'Asal', 'Isi Ekspresi', 'Status', 'Anonim'];
    }

    public function map($expression): array
    {
        return [
            $expression->id,
            $expression->created_at?->format('d/m/Y H:i'),
            $expression->category?->name ?? '-',
            $expression->display_name,
            $expression->origin ?? '-',
            $expression->content,
            $expression->status,
            $expression->is_anonymous ? 'Ya' : 'Tidak',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
