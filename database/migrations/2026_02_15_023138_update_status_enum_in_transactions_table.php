<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY status ENUM(
            'buku_hilang',
            'sudah_dikembalikan',
            'belum_dikembalikan',
            'menunggu_konfirmasi',
            'terlambat',
            'ditolak'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY status ENUM(
            'buku_hilang',
            'sudah_dikembalikan',
            'belum_dikembalikan',
            'menunggu_konfirmasi'
        ) NOT NULL");
    }
};
