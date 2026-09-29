<?php
namespace App\Console\Commands;

use App\Models\PembayaranEvent;
use App\Models\Peserta;
use Illuminate\Console\Command;

class GeneratePembayaranPeserta extends Command
{
    protected $signature = 'pembayaran:generate {--event_id= : ID event spesifik}';
    protected $description = 'Generate pembayaran otomatis untuk peserta yang sudah diterima';

    public function handle()
    {
        $eventId = $this->option('event_id');

        // Ambil SEMUA peserta dengan status 'diterima'
        $query = Peserta::where('status', 'diterima');

        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        $pesertas = $query->get();

        if ($pesertas->isEmpty()) {
            $this->info('Tidak ada peserta dengan status "diterima" di database.');
            return 0;
        }

        $this->info("Ditemukan {$pesertas->count()} peserta dengan status diterima. Memeriksa pembayaran...");

        $createdCount = 0;

        foreach ($pesertas as $peserta) {
            // Cek manual apakah peserta ini sudah punya record pembayaran
            if ($peserta->pembayaran) {
                $this->line("  ⏭️  Peserta #{$peserta->id} ({$peserta->user->nama}) sudah punya pembayaran, skip.");
                continue;
            }

            $event = $peserta->event;
            $tarifDasar = (int) ($event->biaya_pendaftaran ?? 0);
            
            // Generate kode unik 3 digit dari ID peserta
            $kodeUnik = str_pad($peserta->id, 3, '0', STR_PAD_LEFT);
            
            // Nominal unik = biaya dasar + kode unik
            $nominalUnik = $tarifDasar + (int) $kodeUnik;
            
            // Buat order ID yang unik
            $orderId = 'PPA-' . date('Ymd') . '-' . str_pad($peserta->id, 4, '0', STR_PAD_LEFT);

            PembayaranEvent::create([
                'peserta_id'        => $peserta->id,
                'jumlah'            => $nominalUnik,
                'tarif_dasar'       => $tarifDasar,
                'kode_unik'         => $kodeUnik,
                'midtrans_order_id' => $orderId,
                'status_midtrans'   => 'pending',
                'metode'            => 'transfer_bank',
                'tanggal_bayar'     => null,
            ]);

            $this->line("  ✅ Peserta #{$peserta->id} ({$peserta->user->nama}) → Rp " . number_format($nominalUnik, 0, ',', '.'));
            $createdCount++;
        }

        $this->info("Selesai! {$createdCount} pembayaran baru berhasil dibuat.");
        return 0;
    }
}