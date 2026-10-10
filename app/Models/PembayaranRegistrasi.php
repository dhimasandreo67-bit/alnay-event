<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranRegistrasi extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_registrasi';

    protected $fillable = [
        'peserta_id',
        'tipe',              // dp1, dp2, lunas
        'jumlah',
        'tarif_dasar',
        'kode_unik',
        'metode',
        'payment_method',
        'qr_string',
        'va_number',
        'qr_url',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'status_midtrans',
        'tanggal_bayar',
    ];

    protected $casts = [
        'jumlah' => 'float',
        'tarif_dasar' => 'float',
        'tanggal_bayar' => 'datetime',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function scopeLunas($query)
    {
        return $query->where('status_midtrans', 'settlement');
    }

    public function getLabelTipeAttribute(): string
    {
        return match ($this->tipe) {
            'dp1' => 'DP Gelombang 1',
            'dp2' => 'Pelunasan DP',
            'lunas' => 'Lunas',
            default => 'Tidak Diketahui',
        };
    }
}