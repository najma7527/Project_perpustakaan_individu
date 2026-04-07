<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeBuku extends Model
{
    use HasFactory;

    protected $table = 'kode_buku';

    protected $fillable = [
        'buku_id',
        'kode_buku',
        'status',
    ];

    public const STATUS_TERSEDIA = 'tersedia';
    public const STATUS_DIPINJAM = 'dipinjam';
    public const STATUS_HILANG = 'hilang';

    public function book()
    {
        return $this->belongsTo(Book::class, 'buku_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'kode_buku_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'kode_buku_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_TERSEDIA);
    }
}
