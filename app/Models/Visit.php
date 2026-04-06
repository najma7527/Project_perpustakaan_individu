<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visit extends Model
{
    use HasFactory;

    protected $table = 'visit';

    protected $fillable = [
        'user_id',
        'transactions_id',
        'tanggal_datang',
    ];

    protected $casts = [
        'tanggal_datang' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transactions_id');
    }
}
