<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;


class PenggunaEvent extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'pengguna_event';

    // Tidak ada created_at/updated_at, adanya tanggal_daftar (default useCurrent di DB)
    public $timestamps = false;

    protected $fillable = [
        'google_id',
        'nama',
        'email',
        'foto_url',
        'login_terakhir',
    ];

    protected $casts = [
        'tanggal_daftar' => 'datetime',
        'login_terakhir' => 'datetime',
    ];

    public function pesertas(): HasMany
    {
        return $this->hasMany(Peserta::class, 'user_id');
    }

    public function votings(): HasMany
    {
        return $this->hasMany(Voting::class, 'user_id');
    }
}