<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanPeserta extends Model
{
    use HasFactory;

    protected $table = 'jawaban_peserta';

    public $timestamps = false;

    protected $fillable = [
        'peserta_id',
        'pertanyaan_id',
        'jawaban',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }
}