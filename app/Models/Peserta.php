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
        'nama_pendaftaran',
        'status',
        'hasil_akhir',
        'skor_akhir',
        'catatan_admin',
        'galeri_foto',
        'total_vote',
        'answers',
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

    public function votingMasuk(): HasMany
    {
        return $this->hasMany(Voting::class, 'peserta_id');
    }

    public function voting(): HasMany
    {
        return $this->hasMany(Voting::class, 'peserta_id');
    }

    public function isOwnedBy(?PenggunaEvent $user): bool
    {
        return $user !== null && $this->user_id === $user->id;
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'peserta_id');
    }

    /**
     * Ambil nama tampil peserta — prioritas dari jawaban pertanyaan "nama",
     * fallback ke nama_pendaftaran, lalu ke nama akun.
     */
    public function namaTampil(): string
    {
        // Pastikan relasi jawaban + pertanyaan sudah dimuat
        $jawabanList = $this->relationLoaded('jawaban')
            ? $this->jawaban
            : $this->jawaban()->with('pertanyaan')->get();

        $jawabanNama = $jawabanList->first(function ($j) {
            return $j->pertanyaan
                && str_contains(strtolower($j->pertanyaan->label), 'nama');
        });

        if ($jawabanNama && $jawabanNama->jawaban) {
            $val = $jawabanNama->jawaban;

            return is_array($val) ? ($val[0] ?? '') : (string) $val;
        }

        // Fallback ke nama_pendaftaran (data lama)
        if ($this->nama_pendaftaran) {
            return $this->nama_pendaftaran;
        }

        // Fallback ke nama akun
        return $this->user?->nama_custom
            ?: $this->user?->nama
            ?: 'Peserta';
    }

    /**
     * Ambil email peserta — prioritas dari jawaban pertanyaan "email",
     * fallback ke email akun.
     */
    public function emailTampil(): string
    {
        $jawabanList = $this->relationLoaded('jawaban')
            ? $this->jawaban
            : $this->jawaban()->with('pertanyaan')->get();

        $jawabanEmail = $jawabanList->first(function ($j) {
            return $j->pertanyaan
                && str_contains(strtolower($j->pertanyaan->label), 'email');
        });

        if ($jawabanEmail && $jawabanEmail->jawaban) {
            $val = $jawabanEmail->jawaban;

            return is_array($val) ? ($val[0] ?? '') : (string) $val;
        }

        return $this->user?->email ?? '-';
    }
}