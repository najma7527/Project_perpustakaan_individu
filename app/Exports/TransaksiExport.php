<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TransaksiExport implements FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithEvents
{
    private int $rowNumber = 0;
    private $start;
    private $end;
    private $type;

    public function __construct($start = null, $end = null, $type = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->type = $type;
    }

    public function collection()
    {
        return Transaction::with('user', 'book')
            ->when($this->start && $this->end, fn($q) => $q->whereBetween('tanggal_peminjaman', [$this->start, $this->end]))
            ->when($this->type, fn($q) => $q->where('jenis_transaksi', $this->type))
            ->orderBy('tanggal_peminjaman', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Anggota',
            'Judul Buku',
            'Kelas',
            'Jenis Transaksi',
            'Tanggal Pinjam',
            'Tanggal Jatuh Tempo',
            'Tanggal Dikembalikan',
            'Status',
        ];
    }

    public function map($transaction): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $transaction->user->name ?? '-',
            $transaction->book->judul ?? '-',
            $transaction->user->kelas ?? '-',
            $transaction->jenis_transaksi ?? '-',
            $transaction->tanggal_peminjaman ? $transaction->tanggal_peminjaman->format('d/m/Y') : '-',
            $transaction->tanggal_jatuh_tempo ? $transaction->tanggal_jatuh_tempo->format('d/m/Y') : '-',
            $transaction->tanggal_pengembalian ? $transaction->tanggal_pengembalian->format('d/m/Y') : '-',
            $transaction->status == 'sudah_dikembalikan' ? 'Sudah dikembalikan' : ($transaction->status == 'buku_hilang' ? 'Buku Hilang' : 'Belum dikembalikan'),
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            6 => [ // baris header tabel setelah digeser
                'font' => ['bold' => true],
            ],
        ];
    }

    public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {

            $sheet = $event->sheet->getDelegate();

            // Geser tabel ke bawah
            $sheet->insertNewRowBefore(1, 7);

            // =========================
            // TAMBAH LOGO
            // =========================
            $drawing = new Drawing();
            $drawing->setName('Logo Sekolah');
            $drawing->setDescription('Logo SMK');
            $drawing->setPath(public_path('img/logo_smk4.png'));
            $drawing->setHeight(80);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);

            // =========================
            // HEADER SEKOLAH
            // =========================
            $sheet->setCellValue('C1', 'SMK NEGERI 4 BOJONEGORO');
            $sheet->setCellValue('C2', 'PERPUSTAKAAN');
            $sheet->setCellValue('C3', 'JL. RAYA SURABAYA BOJONEGORO, Sukowati, Kec. Kapas, Kab. Bojonegoro, Jawa Timur.');
            $sheet->setCellValue('C4', 'Telp. (0353) 892418 | Email: smkn4bojonegoro@yahoo.co.id');

            $sheet->mergeCells('C1:I1');
            $sheet->mergeCells('C2:I2');
            $sheet->mergeCells('C3:I3');
            $sheet->mergeCells('C4:I4');

            $sheet->getStyle('C1:C2')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            $sheet->getStyle('C3:C4')->applyFromArray([
                'font' => [
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            // =========================
            // HEADER TABEL BIRU
            // =========================
            $sheet->getStyle('A8:I8')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '004D40'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            // =========================
            // STYLE ISI TABEL
            // =========================
            $lastRow = $sheet->getHighestRow();

            $sheet->getStyle("A8:I{$lastRow}")
                  ->getBorders()
                  ->getAllBorders()
                  ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $sheet->getStyle("A9:A{$lastRow}")
                  ->getAlignment()
                  ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Auto width
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // =========================
            // TAMBAHKAN TANDA TANGAN
            // =========================
            $lastRow = $sheet->getHighestRow();
            $signatureStartRow = $lastRow + 3; // Mulai tanda tangan 3 baris setelah data

            // Row 1: Nama
            $sheet->setCellValue('E' . $signatureStartRow, 'Ika Susilowati, S. Pd');

            // Row 2-4: Gambar tanda tangan (menggunakan 3 baris)
            $signatureDrawing = new Drawing();
            $signatureDrawing->setName('Tanda Tangan');
            $signatureDrawing->setDescription('Tanda tangan Pembina Perpustakaan');
            $signatureDrawing->setPath(public_path('img/ttd.png'));
            $signatureDrawing->setHeight(70); // Lebih besar untuk 3 baris
            $signatureDrawing->setCoordinates('E' . ($signatureStartRow + 1)); // Mulai dari row kedua
            $signatureDrawing->setWorksheet($sheet);

            // Row 5: Jabatan
            $sheet->setCellValue('E' . ($signatureStartRow + 4), 'Pembina Perpustakaan');

            // Style untuk nama (bold, center)
            $sheet->getStyle('E' . $signatureStartRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 11,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            // Style untuk jabatan (center)
            $sheet->getStyle('E' . ($signatureStartRow + 4))->applyFromArray([
                'font' => [
                    'size' => 10,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        },
    ];
}
}
