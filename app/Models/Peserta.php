<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Jawaban;
use App\Models\Voting;

class Peserta extends Model
{

    use HasFactory;

    protected $table = 'peserta_event';

    // Tidak ada created_at/updated_at, adanya tanggal_daftar (default useCurrent di DB)
    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',       // menunggu_pembayaran | diterima | ditolak
        'hasil_akhir',
        'skor_akhir',
        'catatan_admin',
        'answers',      // JSON, jawaban peserta
    ];

    protected $casts = [
        'skor_akhir' => 'decimal:2',
        'tanggal_daftar' => 'datetime',
        'answers' => 'array',
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

    public function pembayaran(): HasOne
    {
        return $this->hasOne(PembayaranEvent::class, 'peserta_id');
    }

    // Voting yang MASUK ke peserta ini (dia yang divote)
    public function voting(): HasMany
    {
        return $this->hasMany(Voting::class, 'peserta_id');
    }

    /**
     * Helper buat cek "admin ATAU owner" di controller:
     * if ($peserta->isOwnedBy($request->user())) { ... }
     */
    public function isOwnedBy(?PenggunaEvent $user): bool
    {
        return $user !== null && $this->user_id === $user->id;
    }

    public function jawaban()
    {
        return $this->hasMany(\App\Models\Jawaban::class, 'peserta_id');
    }

    public function votingMasuk()
    {
        return $this->hasMany(\App\Models\Voting::class, 'peserta_id'); 
    }
}