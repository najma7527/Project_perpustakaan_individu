<?php

namespace App\Exports;

use App\Models\Report;
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

class KehilanganExport implements FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithEvents
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
        },
    ];
}
}
