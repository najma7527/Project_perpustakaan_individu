<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bookshelf extends Model
{
    use HasFactory;

    protected $table = 'bookshelf';

    protected $fillable = [
        'no_rak',
        'keterangan',
    ];

    public function rows()
    {
        return $this->hasMany(Row::class, 'rak_id');
    }
}
