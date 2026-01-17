<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Row extends Model
{
    use HasFactory;

    protected $table = 'row';

    protected $fillable = [
        'rak_id',
        'baris_ke',
        'keterangan',
    ];

    public function bookshelf()
    {
        return $this->belongsTo(Bookshelf::class, 'rak_id');
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'id_baris');
    }
}
