<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnggotaDiterimaExport implements FromCollection, WithHeadings, WithMapping
{
    private int $rowNumber = 0;

    public function collection()
    {
        return User::where('role', 'anggota')
            ->where('status', 'aktif')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Username',
            'NIS/NISN',
            'Kelas',
            'No. Telepon',
            'Alamat',
            'Status',
            'Tanggal Daftar',
        ];
    }

    public function map($user): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $user->name,
            $user->username,
            $user->nis_nisn ?? '-',
            $user->kelas ?? '-',
            $user->telephone ?? '-',
            $user->alamat ?? '-',
            ucfirst($user->status),
            $user->created_at->format('d/m/Y'),
        ];
    }
}
