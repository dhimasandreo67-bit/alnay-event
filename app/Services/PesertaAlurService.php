<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Peserta;
use Illuminate\Support\Facades\DB;

class PesertaAlurService
{
    public function daftar(Event $event, array $data): Peserta
    {
        if (!$event->pendaftaran_aktif) {
            throw new \Exception('Pendaftaran untuk event ini sudah ditutup.');
    }
    $biaya = (float) ($event->biaya_pendaftaran ?? 0);
        $statusAwal = $biaya > 0 ? 'menunggu_pembayaran' : 'diterima';

        $peserta = DB::transaction(function () use ($event, $data, $statusAwal) {
            $peserta = $event->pesertas()->create([
                'user_id' => $data['user_id'] ?? null,
                'nama_pendaftaran' => $data['nama_pendaftaran'] ?? 'Peserta',
                'status' => $statusAwal,
                'tampil_di_publik' => false,
                'status_registrasi' => 'belum',
            ]);

            if (!empty($data['jawabans'])) {
                foreach ($data['jawabans'] as $j) {
                    $peserta->jawabans()->create($j);
                }
            }

            return $peserta;
        });

        if ($biaya <= 0) {
            $this->setelahBayar($peserta);
        }

        return $peserta;
    }

    public function setelahBayar(Peserta $peserta): void
    {
        $event = $peserta->event;

        if ($event->butuh_audisi) {
            $peserta->update(['status' => 'menunggu_audisi']);
        } else {
            $this->terima($peserta);
        }
    }

    public function terima(Peserta $peserta): void
    {
        $event = $peserta->event;

        // ✅ Selalu tampil di publik setelah diterima
        // Registrasi TIDAK menghalangi tampil
        $update = [
            'status' => 'diterima',
            'tampil_di_publik' => true,        // ← LANGSUNG TAMPIL
        ];

        // Kalau TIDAK butuh registrasi — anggap lunas
        if (!$event->butuh_registrasi) {
            $update['status_registrasi'] = 'lunas';
        }

        $peserta->update($update);
    }

    public function tolak(Peserta $peserta, ?string $catatan = null): void
    {
        $peserta->update([
            'status' => 'ditolak',
            'catatan_admin' => $catatan,
        ]);
    }

    public function selesaikanRegistrasi(Peserta $peserta): void
    {
        $peserta->update([
            'status_registrasi' => 'lunas',
            'tampil_di_publik' => true,
        ]);
    }

    public function tampilkan(Peserta $peserta): void
    {
        if ($peserta->status !== 'diterima') {
            throw new \Exception('Peserta belum diterima, tidak bisa ditampilkan.');
        }
        $peserta->update(['tampil_di_publik' => true]);
    }

    public function sembunyikan(Peserta $peserta): void
    {
        $peserta->update(['tampil_di_publik' => false]);
    }

    public function tambahManual(Event $event, array $data): Peserta
    {
        return DB::transaction(function () use ($event, $data) {
            $peserta = $event->pesertas()->create([
                'user_id'          => $data['user_id'] ?? null,
                'nama_pendaftaran' => $data['nama_pendaftaran'] ?? 'Peserta Manual',
                'status'           => 'diterima',        // ✅ langsung diterima
                'status_registrasi'=> 'lunas',            // ✅ anggap lunas
                'tampil_di_publik' => true,               // ✅ langsung tampil
                'kandidat_voting'  => false,              // ✅ FIX: ini peserta pendaftaran biasa, bukan kandidat voting
                'hasil_audisi'     => null,               // ✅ nggak ada audisi
            ]);

            if (!empty($data['jawabans'])) {
                foreach ($data['jawabans'] as $j) {
                    $peserta->jawabans()->create($j);
                }
            }

            return $peserta;
        });
    }
}