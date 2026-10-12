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
        'sudah_audisi',        
        'tanggal_audisi',      
        'status_registrasi',
        'tampil_di_publik',  
    ];

    protected $casts = [
        'skor_akhir' => 'decimal:2',
        'tanggal_daftar' => 'datetime',
        'galeri_foto' => 'array',
        'answers' => 'array',
        'sudah_audisi' => 'boolean',      
        'tanggal_audisi' => 'datetime',
        'tampil_di_publik' => 'boolean',
    ];

    // ============================================
    // RELASI
    // ============================================

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

    /** Relasi BARU — pembayaran registrasi */
    public function pembayaranRegistrasi(): HasMany
    {
        return $this->hasMany(PembayaranRegistrasi::class, 'peserta_id');
    }

    public function votingMasuk(): HasMany
    {
        return $this->hasMany(Voting::class, 'peserta_id');
    }

    public function voting(): HasMany
    {
        return $this->hasMany(Voting::class, 'peserta_id');
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'peserta_id');
    }

    // ============================================
    // HELPER
    // ============================================

    public function isOwnedBy(?PenggunaEvent $user): bool
    {
        return $user !== null && $this->user_id === $user->id;
    }

    /** Apakah peserta sudah lunas registrasi? */
    public function sudahLunasRegistrasi(): bool
    {
        return $this->status_registrasi === 'lunas';
    }

    /** Apakah peserta sudah bayar DP? */
    public function sudahBayarDp(): bool
    {
        return in_array($this->status_registrasi, ['dp', 'lunas'], true);
    }

    public function namaTampil(): string
    {
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

        if ($this->nama_pendaftaran) {
            return $this->nama_pendaftaran;
        }

        return $this->user?->nama_custom
            ?: $this->user?->nama
            ?: 'Peserta';
    }

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