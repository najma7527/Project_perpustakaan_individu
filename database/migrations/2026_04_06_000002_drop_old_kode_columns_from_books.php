<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'kode_buku')) {
                $table->dropColumn('kode_buku');
            }
            if (Schema::hasColumn('books', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('kode_buku')->unique()->nullable();
            $table->enum('status', ['tersedia', 'dipinjam'])->default('tersedia');
        });
    }
};
