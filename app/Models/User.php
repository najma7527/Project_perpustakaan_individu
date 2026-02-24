<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Notification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'nis_nisn',
        'telephone',
        'role',
        'password',
        'kelas',
        'status',
        'profile_photo',
        'alamat',
        'tanggal_pengajuan',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    // 🔔 RELASI NOTIFIKASI
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}