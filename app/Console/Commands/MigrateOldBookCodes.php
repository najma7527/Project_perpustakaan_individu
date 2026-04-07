<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\KodeBuku;
use Illuminate\Console\Command;

class MigrateOldBookCodes extends Command
{
    protected $signature = 'migrate:old-book-codes';
    protected $description = 'Pindahkan kode_buku dan status lama dari books ke tabel kode_buku.';

    public function handle(): int
    {
        $this->info('Mulai migrasi kode buku lama...');

        $migrated = 0;

        foreach (Book::with('kodeBuku')->cursor() as $book) {
            // Jika book sudah memiliki kode buku baru, skip.
            if ($book->kodeBuku()->exists()) {
                continue;
            }

            if (empty($book->kode_buku)) {
                $this->warn("Buku ID {$book->id} ('{$book->judul}') tidak memiliki kode_buku lama, dilewati.");
                continue;
            }

            KodeBuku::create([
                'buku_id' => $book->id,
                'kode_buku' => $book->kode_buku,
                'status' => $book->status ?? KodeBuku::STATUS_TERSEDIA,
            ]);

            $migrated++;
        }

        $this->info("Selesai. Total kode buku lama dipindahkan: {$migrated}.");
        $this->warn('Jalankan migration drop kolom books setelah Anda yakin data lama sudah aman tertransfer.');

        return 0;
    }
}
