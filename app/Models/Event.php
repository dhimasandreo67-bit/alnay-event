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
    'status',
    'pendaftaran_dibuka',
    'tampil_di_beranda',
    'syarat_ketentuan',
    'biaya_pendaftaran',
    'harga_per_vote',
    'harga_registrasi',        // ← TAMBAH
    'harga_registrasi_dp',     // ← TAMBAH
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
    'harga_registrasi' => 'float',         // ← TAMBAH
    'harga_registrasi_dp' => 'float',      // ← TAMBAH
    'kuota_peserta' => 'integer',
    'voting_aktif' => 'boolean',
    'auto_freeze_voting' => 'boolean',
    'blackout_h2_voting' => 'boolean',
    'voting_mulai' => 'datetime',
    'voting_selesai' => 'datetime',
    'tampilkan_hasil_voting' => 'boolean',
    'form_fields' => 'array',
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

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'akan_datang' => 'Akan Datang',
            'sedang_berlangsung' => 'Sedang Berlangsung',
            'telah_berakhir' => 'Telah Berakhir',
            default => 'Tidak Diketahui',
        };
    }
}