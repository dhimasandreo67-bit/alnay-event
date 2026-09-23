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
        'metode',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'status_midtrans',
        'tanggal_bayar',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'datetime',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function isLunas(): bool
    {
        return $this->status_midtrans === 'settlement';
    }
}