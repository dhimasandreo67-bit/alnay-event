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
        'deskripsi',
        'form_fields',
        'banner',
        'syarat_ketentuan',
        'biaya_pendaftaran',
        'harga_per_vote',
        'tanggal_mulai',
        'waktu_mulai',
        'tanggal_selesai',
        'waktu_selesai',
        'lokasi',
        'batas_pendaftaran_tanggal',
        'batas_pendaftaran_waktu',
        'kuota_peserta',
        'mode_penilaian',
        'voting_aktif',
        'tampilkan_hasil_voting',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
        'batas_pendaftaran_tanggal' => 'date',
        'batas_pendaftaran_waktu' => 'datetime:H:i',
        'biaya_pendaftaran' => 'decimal:2',
        'harga_per_vote' => 'decimal:2',
        'kuota_peserta' => 'integer',
        'voting_aktif' => 'boolean',
        'tampilkan_hasil_voting' => 'boolean',
        'form_fields' => 'array',
    ];

    protected $appends = ['status', 'status_label'];

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

        /**
     * Status dihitung dari tanggal + waktu mulai/selesai (versi han).
     * Kalau waktu kosong, dianggap 00:00 (mulai) dan 23:59 (selesai),
     * jadi event lama milik main tetap dihitung sama seperti sebelumnya.
     */
    public function getStatusAttribute(): string
    {
        $mulai = $this->combineDateTime($this->tanggal_mulai, $this->waktu_mulai, '00:00');
        $selesai = $this->tanggal_selesai
            ? $this->combineDateTime($this->tanggal_selesai, $this->waktu_selesai, '23:59')
            : null;

        $sekarang = Carbon::now();

        if ($sekarang->lt($mulai)) {
            return 'akan_datang';
        }

        if ($selesai && $sekarang->gt($selesai)) {
            return 'selesai';
        }

        if (! $selesai && $sekarang->gt($mulai->copy()->endOfDay())) {
            return 'selesai';
        }

        return 'berlangsung';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'akan_datang' => 'Akan Datang',
            'berlangsung' => 'Sedang Berlangsung',
            'selesai' => 'Telah Berakhir',
        };
    }

    private function combineDateTime($date, $time, string $fallbackTime): Carbon
    {
        $tanggal = Carbon::parse($date)->format('Y-m-d');
        $jam = $time ? Carbon::parse($time)->format('H:i:s') : $fallbackTime . ':00';

        return Carbon::parse("{$tanggal} {$jam}");
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
