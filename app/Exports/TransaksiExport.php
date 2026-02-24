<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping
{
    private int $rowNumber = 0;

    public function collection()
    {
        return Transaction::with('user', 'book')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama User',
            'Judul Buku',
            'Tipe',
            'Tanggal',
        ];
    }

    public function map($transaction): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $transaction->user->name ?? '-',
            $transaction->book->title ?? '-',
            $transaction->type,
            $transaction->created_at,
        ];
    }
}
