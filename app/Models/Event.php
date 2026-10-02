<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'event';

    protected $fillable = [
        'nama',
        'deskripsi',
        'is_active',
        'form_fields',
        'banner',
        'status',
        'syarat_ketentuan',
        'biaya_pendaftaran',
        'harga_per_vote',
        'tanggal_mulai',
        'waktu_mulai',
        'tanggal_selesai',
        'waktu_selesai',
        'pembukaan_pendaftaran_tanggal',
        'pembukaan_pendaftaran_waktu',
        'lokasi',
        'batas_pendaftaran_tanggal',
        'batas_pendaftaran_waktu',
        'kuota_peserta',
        'mode_penilaian',
        'voting_mulai',
        'voting_aktif',
        'voting_selesai',
        'auto_freeze_voting',
        'blackout_h2_voting',
        'format_hasil_voting',
        'tampilkan_hasil_voting',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
        'pembukaan_pendaftaran_tanggal' => 'date',
        'pembukaan_pendaftaran_waktu' => 'datetime:H:i',
        'batas_pendaftaran_tanggal' => 'date',
        'batas_pendaftaran_waktu' => 'datetime:H:i',
        'biaya_pendaftaran' => 'float', 
        'harga_per_vote' => 'float',
        'kuota_peserta' => 'integer',
        'voting_aktif' => 'boolean',
        'auto_freeze_voting' => 'boolean',
        'blackout_h2_voting' => 'boolean',
        'voting_mulai' => 'datetime',
        'voting_selesai' => 'datetime',
        'tampilkan_hasil_voting' => 'boolean',
        'form_fields' => 'array',
        'tanggal_mulai_pendaftaran' => 'datetime',
        'tanggal_selesai_pendaftaran' => 'datetime',
        'is_active' => 'boolean',
    ];

    // protected $appends = ['status', 'status_label'];

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
     * Status DIHITUNG dari tanggal+waktu mulai/selesai, bukan kolom DB.
     * Menggabungkan tanggal_mulai (date) + waktu_mulai (time, opsional)
     * supaya perhitungan tetap akurat sampai ke jam.
     */

    // public function getStatusAttribute()
    // {
    //     // Jika event di-nonaktifkan secara manual
    //     if (isset($this->is_active) && !$this->is_active) {
    //         return 'nonaktif';
    //     }

    //     $now = Carbon::now();
    //     $mulai = $this->tanggal_mulai_pendaftaran ? Carbon::parse($this->tanggal_mulai_pendaftaran) : null;
    //     $selesai = $this->tanggal_selesai_pendaftaran ? Carbon::parse($this->tanggal_selesai_pendaftaran) : null;

    //     if ($mulai && $now->lt($mulai)) {
    //         return 'akan_datang';
    //     }

    //     if ($selesai && $now->gt($selesai)) {
    //         return 'selesai';
    //     }

    //     return 'berlangsung';
    // }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'akan_datang' => 'Akan Datang',
            'sedang_berlangsung' => 'Sedang Berlangsung',
            'telah_berakhir' => 'Telah Berakhir',
        };
    }

    // private function combineDateTime($date, $time, string $fallbackTime): Carbon
    // {
    //     $tanggal = Carbon::parse($date)->format('Y-m-d');
    //     $jam = $time ? Carbon::parse($time)->format('H:i:s') : $fallbackTime.':00';

    //     return Carbon::parse("{$tanggal} {$jam}");
    // }

    // public function scopeWithStatus($query, string $status)
    // {
    //     // Catatan: filter status dilakukan di memori (bukan query SQL) kalau
    //     // butuh presisi jam, karena kombinasi tanggal+waktu sulit di-query
    //     // langsung. Untuk dataset besar, pertimbangkan generated column.
    //     return $query->get()->filter(fn ($event) => $event->status === $status);
    // }
}