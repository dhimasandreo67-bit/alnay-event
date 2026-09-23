<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voting extends Model
{
    use HasFactory;

    protected $table = 'voting';

    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'peserta_id',
        'user_id',
        'nama_tampilan',
        'jumlah_vote',
        'harga_per_vote',   // snapshot harga saat transaksi, JANGAN ambil ulang dari event
        'total_bayar',
        'midtrans_order_id',
        'status_midtrans',
    ];

    protected $casts = [
        'harga_per_vote' => 'decimal:2',
        'total_bayar' => 'decimal:2',
        'tanggal_vote' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Peserta yang divote
    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    // Pelanggan yang melakukan vote (identitas asli, buat audit)
    public function voter(): BelongsTo
    {
        return $this->belongsTo(PenggunaEvent::class, 'user_id');
    }

    public function isLunas(): bool
    {
        return $this->status_midtrans === 'settlement';
    }
}