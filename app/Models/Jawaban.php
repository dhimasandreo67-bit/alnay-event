<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang sesuai di database kamu
    protected $table = 'jawaban_peserta'; // <-- Atau 'pendaftaran_jawaban' / nama tabel di database kamu

    protected $fillable = [
        'peserta_id',
        'pertanyaan_id',
        'jawaban', // atau nama field tempat menyimpan isi jawaban
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }
}