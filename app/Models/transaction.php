<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'buku_id',
        'kode_buku_id',
        'tanggal_peminjaman',
        'tanggal_jatuh_tempo',
        'tanggal_pengembalian',
        'jenis_transaksi',
        'status',
    ];

    protected $casts = [
        'tanggal_peminjaman' => 'date',
        'tanggal_jatuh_tempo' => 'date',
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

    public function kodeBuku()
    {
        return $this->belongsTo(KodeBuku::class, 'kode_buku_id');
    }

   public function reports()
    {
        return $this->hasOne(Report::class, 'transactions_id');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'transactions_id');
    }

}
