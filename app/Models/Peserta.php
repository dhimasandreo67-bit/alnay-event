<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta_event';

    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',       // menunggu_pembayaran | diterima | ditolak
        'hasil_akhir',
        'skor_akhir',
        'catatan_admin',
    ];

    protected $casts = [
        'skor_akhir' => 'decimal:2',
        'tanggal_daftar' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(PenggunaEvent::class, 'user_id');
    }

    public function jawabans(): HasMany
    {
        return $this->hasMany(JawabanPeserta::class, 'peserta_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(PembayaranEvent::class, 'peserta_id');
    }

    public function votingMasuk(): HasMany
    {
        return $this->hasMany   (Voting::class, 'peserta_id');
    }

    public function isOwnedBy(?PenggunaEvent $user): bool
    {
        return $user !== null && $this->user_id === $user->id;
    }
}