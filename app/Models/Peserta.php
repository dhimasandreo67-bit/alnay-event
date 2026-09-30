<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Jawaban;
use App\Models\Voting;

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
        'galeri_foto',
        'total_vote',
        'answers',      // dari han: JSON jawaban peserta
    ];

    protected $casts = [
        'skor_akhir' => 'decimal:2',
        'tanggal_daftar' => 'datetime',
        'galeri_foto' => 'array',
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

    public function pembayaran(): HasMany
    {
        return $this->hasMany(PembayaranEvent::class, 'peserta_id');
    }

    /**
     * Voting yang MASUK ke peserta ini (dia yang divote)
     * Nama asli di mainv2
     */
    public function votingMasuk(): HasMany
    {
        return $this->hasMany(Voting::class, 'peserta_id');
    }

    /**
     * Alias dari han — supaya kompatibel dengan kode yang pakai voting()
     */
    public function voting(): HasMany
    {
        return $this->hasMany(Voting::class, 'peserta_id');
    }

    public function isOwnedBy(?PenggunaEvent $user): bool
    {
        return $user !== null && $this->user_id === $user->id;
    }

    // Dari han: dipakai halaman admin Blade
    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'peserta_id');
    }
}