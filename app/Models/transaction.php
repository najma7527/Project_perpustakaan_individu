<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_peminjaman',
        'jatuh_tempo',
        'tanggal_pengembalian',
        'status',
    ];

    protected $casts = [
        'tanggal_peminjaman' => 'date',
        'jatuh_tempo' => 'date',
        'tanggal_pengembalian' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'buku_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'transactions_id');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'transactios_id');
    }
}
