<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $table = 'report';

    protected $fillable = [
        'transactions_id',
        'tanggal_dikembalikan',
        'status',
        'keterangan',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transactions_id');
    }
}
