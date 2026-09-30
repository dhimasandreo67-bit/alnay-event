<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'konfigurasi',
        'wajib',
        'urutan',
        'tag_tampilan',
    ];

    protected $casts = [
        'konfigurasi' => 'array',
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

        /**
     * Baca: selalu array (dibutuhkan kode han).
     * Simpan: string JSON dari main dipakai apa adanya, array dari han di-encode satu kali.
     */
    protected function pilihanJawaban(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value === null || $value === '') {
                    return null;
                }

                if (is_array($value)) {
                    return $value;
                }

                $decoded = json_decode($value, true);

                return is_array($decoded) ? $decoded : [$value];
            },
            set: fn ($value) => is_array($value)
                ? json_encode($value, JSON_UNESCAPED_UNICODE)
                : $value,
        );
    }
}