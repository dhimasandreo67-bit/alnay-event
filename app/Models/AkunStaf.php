<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AkunStaf extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'akun_staf';

    // Tabel ini tidak punya created_at/updated_at, adanya 'dibuat_pada'
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password_hash',
        'last_active_event_id',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'password_hash' => 'hashed',
        'dibuat_pada' => 'datetime',
    ];

    /**
     * Laravel's Authenticatable secara default cari kolom 'password'.
     * Kita override supaya pakai 'password_hash' sesuai kolom asli.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function lastActiveEvent()
    {
        return $this->belongsTo(Event::class, 'last_active_event_id');
    }
}