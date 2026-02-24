<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KehilanganExport implements FromCollection, WithHeadings, WithMapping
{
    private int $rowNumber = 0;

    public function collection()
    {
        return Report::with(['user', 'transaction.book'])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama User',
            'Judul Buku',
            'Keterangan',
            'Tanggal',
        ];
    }

    public function map($report): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $report->user->name ?? '-',
            $report->transaction->book->title ?? '-',
            $report->description,
            $report->created_at,
        ];
    }
}
