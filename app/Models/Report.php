<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'user_id',
        'transactions_id',
        'kode_buku_id',
        'tanggal_ganti',
        'status',
        'keterangan',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transactions_id');
    }

    public function kodeBuku()
    {
        return $this->belongsTo(KodeBuku::class, 'kode_buku_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
