<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AkunStaf extends Authenticatable
{
    protected $table = 'akun_staf';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password_hash',
        'dibuat_pada',
    ];

    // Kolom kita bernama password_hash, bukan password (default Laravel)
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}