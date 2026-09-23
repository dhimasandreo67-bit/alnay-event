<?php

namespace App\Http\Controllers;

use App\Models\PembayaranEvent;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    // POST /peserta/{peserta}/bayar
    public function bayar(Request $request, Peserta $peserta)
    {
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            abort(403, 'Bukan pendaftaran milik kamu.');
        }

        $jumlah = $peserta->event->biaya_pendaftaran;
        $orderId = 'PENDAFTARAN-'.$peserta->id.'-'.Str::random(8);

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        $snapToken = Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $jumlah,
            ],
            'customer_details' => [
                'first_name' => $request->user()->nama,
                'email' => $request->user()->email,
            ],
        ]);

        $pembayaran = PembayaranEvent::create([
            'peserta_id' => $peserta->id,
            'jumlah' => $jumlah,
            'midtrans_order_id' => $orderId,
            'status_midtrans' => 'pending',
        ]);

        return response()->json([
            'snap_token' => $snapToken,
            'order_id' => $orderId,
            'pembayaran' => $pembayaran,
        ]);
    }

    // POST /peserta/midtrans/webhook
    public function webhook(Request $request)
    {
        $signature = hash('sha512', (string) $request->input('order_id')
            .(string) $request->input('status_code')
            .(string) $request->input('gross_amount')
            .config('services.midtrans.server_key'));

        if (! hash_equals($signature, (string) $request->input('signature_key'))) {
            abort(403, 'Signature tidak valid');
        }

        $pembayaran = PembayaranEvent::where('midtrans_order_id', $request->order_id)->firstOrFail();

        $pembayaran->update([
            'midtrans_transaction_id' => $request->transaction_id,
            'status_midtrans' => $request->transaction_status,
            'metode' => $request->payment_type,
            'tanggal_bayar' => $request->transaction_status === 'settlement' ? now() : $pembayaran->tanggal_bayar,
        ]);

        // Peserta otomatis diterima setelah pembayaran lunas (settlement).
        if ($request->transaction_status === 'settlement') {
            $pembayaran->peserta->update(['status' => 'diterima']);
        }

        return response()->json(['message' => 'OK']);
    }
}