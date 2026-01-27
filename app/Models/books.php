<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'judul',
        'pengarang',
        'tahun_terbit',
        'kategori_buku',
        'id_baris',
    ];

    public function row()
    {
        return $this->belongsTo(Row::class, 'id_baris');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'buku_id');
    }
}
