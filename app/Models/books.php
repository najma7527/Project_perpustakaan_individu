<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\transaction;

class books extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'judul',
        'penerbit',    
        'tahun_terbit',
        'genre',
        'stok',
    ];

    public function transactions()
    {
        return $this->hasMany(transaction::class, 'buku_id');
    }
}
