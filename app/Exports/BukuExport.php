<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BukuExport implements FromCollection, WithHeadings, WithMapping
{
    private int $rowNumber = 0;

    public function collection()
    {
        return Book::with('row.bookshelf')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Buku',
            'Judul',
            'Pengarang',
            'Tahun Terbit',
            'Kategori',
            'Status',
            'Rak',
        ];
    }

    public function map($book): array
    {
        $this->rowNumber++;

        $rak = '-';
        if ($book->row && $book->row->bookshelf) {
            $rak = $book->row->bookshelf->no_rak . ' - ' . $book->row->baris_ke;
        }

        return [
            $this->rowNumber,
            $book->kode_buku ?? '-',
            $book->judul,
            $book->pengarang,
            $book->tahun_terbit,
            $book->kategori_buku == 'fiksi' ? 'Fiksi' : 'Non Fiksi',
            ucfirst($book->status ?? '-'),
            $rak,
        ];
    }
}
