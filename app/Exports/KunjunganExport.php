<?php

namespace App\Exports;

use App\Models\Visit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KunjunganExport implements FromCollection, WithHeadings, WithMapping
{
    private int $rowNumber = 0;

    public function collection()
    {
        return Visit::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama User',
            'Tanggal',
        ];
    }

    public function map($visit): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $visit->user->name ?? '-',
            $visit->created_at,
        ];
    }
}
