<?php
namespace App\Console\Commands;

use App\Models\PembayaranEvent;
use App\Models\Peserta;
use Illuminate\Console\Command;

class GeneratePembayaranPeserta extends Command
{
    protected $signature = 'pembayaran:generate {--event_id= : ID event spesifik}';
    protected $description = 'Generate pembayaran otomatis untuk peserta yang sudah diterima tapi belum punya record pembayaran';

    public function handle()
    {
        $eventId = $this->option('event_id');

        $query = Peserta::where('status', 'diterima')
            ->whereDoesntHave('pembayaran');

        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        $pesertas = $query->get();

        if ($pesertas->isEmpty()) {
            $this->info('Tidak ada peserta yang perlu di-generate pembayarannya.');
            return 0;
        }

        $this->info("Ditemukan {$pesertas->count()} peserta yang perlu di-generate pembayarannya.");

        foreach ($pesertas as $peserta) {
            $event = $peserta->event;
            $tarifDasar = (int) ($event->tarif_pendaftaran ?? 0);
            $kodeUnik = str_pad($peserta->id, 3, '0', STR_PAD_LEFT);
            $nominalUnik = $tarifDasar + (int) $kodeUnik;
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
        }

        $this->info('Selesai! Semua pembayaran berhasil di-generate.');
        return 0;
    }
}