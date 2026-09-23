<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class PenggunaEvent extends Authenticatable
{
    protected $table = 'pengguna_event';

    public $timestamps = false;

    protected $fillable = [
        'google_id',
        'nama',
        'email',
        'foto_url',
        'tanggal_daftar',
        'login_terakhir',
    ];

    public function getAuthPassword()
    {
        return '';
    }
}