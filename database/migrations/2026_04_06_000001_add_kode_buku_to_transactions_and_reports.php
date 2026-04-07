<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('kode_buku_id')->nullable()->after('buku_id')->constrained('kode_buku')->nullOnDelete();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('kode_buku_id')->nullable()->after('transactions_id')->constrained('kode_buku')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['kode_buku_id']);
            $table->dropColumn('kode_buku_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['kode_buku_id']);
            $table->dropColumn('kode_buku_id');
        });
    }
};
