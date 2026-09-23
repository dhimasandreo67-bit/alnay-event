<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan_event';

    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'label',
        'tipe_input',
        'pilihan_jawaban',
        'wajib',
        'urutan',
    ];

    protected $casts = [
        'wajib' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function jawabans(): HasMany
    {
        return $this->hasMany(JawabanPeserta::class, 'pertanyaan_id');
    }
}