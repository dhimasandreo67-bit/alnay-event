<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'banner',
        'syarat_ketentuan',
        'biaya_pendaftaran',
        'harga_per_vote',
        'tanggal_mulai',
        'tanggal_selesai',
        'mode_penilaian',
        'voting_aktif',
        'tampilkan_hasil_voting',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'biaya_pendaftaran' => 'decimal:2',
        'harga_per_vote' => 'decimal:2',
        'voting_aktif' => 'boolean',
        'tampilkan_hasil_voting' => 'boolean',
    ];

    protected $appends = ['status'];

    public function pertanyaans(): HasMany
    {
        return $this->hasMany(Pertanyaan::class, 'event_id')->orderBy('urutan');
    }

    public function pesertas(): HasMany
    {
        return $this->hasMany(Peserta::class, 'event_id');
    }

    public function votings(): HasMany
    {
        return $this->hasMany(Voting::class, 'event_id');
    }

    public function getStatusAttribute(): string
    {
        $hariIni = Carbon::now()->startOfDay();

        if ($hariIni->lt(Carbon::parse($this->tanggal_mulai))) {
            return 'akan_datang';
        }

        if ($hariIni->gt(Carbon::parse($this->tanggal_selesai))) {
            return 'selesai';
        }

        return 'berlangsung';
    }

    public function scopeWithStatus($query, string $status)
    {
        $hariIni = Carbon::now()->toDateString();

        return match ($status) {
            'akan_datang' => $query->where('tanggal_mulai', '>', $hariIni),
            'selesai' => $query->where('tanggal_selesai', '<', $hariIni),
            'berlangsung' => $query->where('tanggal_mulai', '<=', $hariIni)
                                   ->where('tanggal_selesai', '>=', $hariIni),
            default => $query,
        };
    }
}
