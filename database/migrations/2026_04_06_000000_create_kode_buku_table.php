<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kode_buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buku_id')->constrained('books')->onDelete('cascade');
            $table->string('kode_buku')->unique();
            $table->enum('status', ['tersedia', 'dipinjam', 'hilang'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kode_buku');
    }
};
