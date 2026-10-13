<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranEvent extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_event';
    public $timestamps = false;

    protected $fillable = [
        'peserta_id',
        'jumlah',
        'tarif_dasar',
        'metode',
        'payment_method',
        'qr_string',
        'qr_url',
        'va_number',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'status_midtrans',
        'tanggal_bayar',
    ];

    protected $casts = [
        'jumlah'        => 'integer',
        'tarif_dasar'   => 'integer',
        'tanggal_bayar' => 'datetime',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function getIsLunasAttribute(): bool
    {
        return $this->status_midtrans === 'settlement' || $this->status_midtrans === 'paid';
    }

    public function getNominalPembayaranAttribute(): string
    {
        if (!$this->jumlah) {
            return 'Belum ada tagihan';
        }
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }
}