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
        'nama_pemilih',
        'email_pemilih',
        'nama_tampilan',
        'jumlah_vote',
        'harga_per_vote',
        'total_bayar',
        'metode_pembayaran',
        'va_number',
        'qr_url',
        'expired_at',
        'midtrans_order_id',
        'status_midtrans',
        'pesan_dukungan',
        'tanggal_vote',
    ];

    protected $casts = [
        'harga_per_vote' => 'decimal:2',
        'total_bayar' => 'decimal:2',
        'tanggal_vote' => 'datetime',
        'expired_at' => 'datetime',
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
