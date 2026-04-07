<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all existing books
        $books = DB::table('books')->get();

        foreach ($books as $index => $book) {
            // Generate kode_buku like BK001, BK002, etc.
            $kodeBuku = 'BK' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            DB::table('kode_buku')->insert([
                'buku_id' => $book->id,
                'kode_buku' => $kodeBuku,
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Truncate the kode_buku table to remove populated data
        DB::table('kode_buku')->truncate();
    }
};
