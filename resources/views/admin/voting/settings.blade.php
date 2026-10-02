<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    @include('admin.partials.pwa-head')
    <title>Pengaturan Event &amp; Voting | ALNAY.ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { gold: '#ba8320', cream: '#f7f5f0' } } } };
    </script>
</head>
<body class="bg-cream font-['Inter'] text-[13px] text-[#22201d]">
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['active' => 'voting-settings', 'event' => $event])

    <main class="flex-1 min-w-0 p-6 pb-24 ml-[235px]">
        <h1 class="text-2xl font-bold m-0">Pengaturan Event &amp; Voting</h1>
        <p class="text-[#73716c] text-[12px] mt-1 max-w-2xl mb-5">
            Konfigurasi status publikasi, periode voting, format tampilan hasil, dan catatan rekening settlement.
        </p>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-emerald-50 text-emerald-700 px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif

        {{-- HANYA SATU blok @if/@else/@endif di sini. Sebelumnya ada 2 @if
             bertumpuk tapi cuma 1 @endif, bikin ParseError "unexpected end of file". --}}
        @if (!$event)
            <div class="bg-white border border-dashed border-[#e3ded6] rounded-xl p-8 text-center text-[#98958f]">
                Belum ada event. Buat event dulu di halaman "Buat Event Baru".
            </div>
        @else
            <form method="POST" action="{{ route('admin.voting.settings.update', $event) }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Visibilitas & Status Publikasi --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-start gap-3">
                                <div>
                                    <h2 class="font-bold text-[15px] m-0">Visibilitas &amp; Status Publikasi</h2>
                                    <p class="text-[11px] text-[#73716c] mt-1">Tentukan ketersediaan event untuk pendaftaran duta dan pemilihan kurator umum.</p>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="hidden" name="voting_aktif" value="0">
                                <input type="checkbox" name="voting_aktif" value="1" {{ $event->voting_aktif ? 'checked' : '' }}>
                                <span class="toggle-track"><span class="toggle-thumb"></span></span>
                            </label>
                        </div>
                        <div class="bg-[#fdf6e8] border border-[#f0dfae] rounded-lg p-3 flex items-center justify-between text-[12px]">
                            <div>
                                <strong class="block">Event Aktif &amp; Siap Menerima Suara</strong>
                                <span class="text-[#8a7a4f]">Terbuka untuk masyarakat umum melalui landing page portal resmi.</span>
                            </div>
                            <span class="bg-gold/10 text-gold border border-gold/40 text-[10px] font-bold px-2 py-1 rounded whitespace-nowrap ml-3">PUBLIK</span>
                        </div>
                    </div>

                    {{-- Periode Waktu & Countdown --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-5">
                        <div class="flex items-start gap-3 mb-3">
                            <div>
                                <h2 class="font-bold text-[15px] m-0">Periode Waktu &amp; Countdown</h2>
                                <p class="text-[11px] text-[#73716c] mt-1">Jadwal pembukaan dan penutupan otomatis sistem voting.</p>
                            </div>
                        </div>

                        <label class="block text-[11px] font-bold text-[#73716c] mb-1">Mulai Pemilihan (Kick-off)</label>
                        <input type="datetime-local" id="votingMulai" name="voting_mulai"
                               value="{{ optional($event->voting_mulai)->format('Y-m-d\TH:i') }}"
                               class="w-full border border-[#e5e1db] rounded-lg px-3 py-2 text-[12px] mb-3">

                        <label class="block text-[11px] font-bold text-[#73716c] mb-1">Batas Akhir Voting (Penutupan)</label>
                        <input type="datetime-local" id="votingSelesai" name="voting_selesai"
                               value="{{ optional($event->voting_selesai)->format('Y-m-d\TH:i') }}"
                               class="w-full border border-[#e5e1db] rounded-lg px-3 py-2 text-[12px] mb-3">

                        <div id="durasiInfo" class="bg-[#f7f5f0] rounded-lg p-2.5 text-[11px] flex justify-between mb-4">
                            <span>Durasi Aktif: <strong id="durasiTeks">-</strong></span>
                            <span class="text-[#98958f]">WIB (GMT+7)</span>
                        </div>

                        <label class="flex items-start gap-2 mb-3 text-[12px]">
                            <input type="checkbox" name="auto_freeze_voting" value="1" class="mt-0.5" {{ $event->auto_freeze_voting ? 'checked' : '' }}>
                            <span><strong class="block">Kunci Suara Otomatis (Auto Freeze)</strong>
                            <span class="text-[#73716c] text-[11px]">Tutup perolehan secara sistem tepat saat tenggat waktu tercapai tanpa jeda.</span></span>
                        </label>

                        <label class="flex items-start gap-2 text-[12px]">
                            <input type="checkbox" name="blackout_h2_voting" value="1" class="mt-0.5" {{ $event->blackout_h2_voting ? 'checked' : '' }}>
                            <span><strong class="block">Mode Rahasia H-2 (Blackout Leaderboard)</strong>
                            <span class="text-[#73716c] text-[11px]">Sembunyikan total suara publik 48 jam sebelum grand final demi unsur kejutan.</span></span>
                        </label>
                    </div>

                    {{-- Format Tampilan Hasil Vote --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-start gap-3">
                                <div>
                                    <h2 class="font-bold text-[15px] m-0">Format Tampilan Hasil Vote</h2>
                                </div>
                            </div>
                            <span class="bg-white border border-gold text-gold text-[10px] font-bold px-2 py-1 rounded whitespace-nowrap">TAMPILAN PUBLIK</span>
                        </div>

                        @foreach ([
                            'angka' => ['Angka Vote Asli', 'Menampilkan jumlah nominal suara terkumpul riil (Contoh: 1.420 suara)', '1.420 Suara'],
                            'persentase' => ['Persentase (%)', 'Menampilkan persentase proporsional dari total suara masuk (Contoh: 34,8%)', '34,8%'],
                            'keduanya' => ['Tampilkan Keduanya', 'Menampilkan angka riil diikuti persentase (Contoh: 1.420 suara (34,8%))', '1.420 (34,8%)'],
                        ] as $value => [$label, $desc, $contoh])
                            <label class="flex items-center justify-between border rounded-lg p-3 mb-2 cursor-pointer format-option {{ $event->format_hasil_voting === $value ? 'border-gold bg-[#fff8e9]' : 'border-[#e5e1db]' }}">
                                <span class="flex items-start gap-2">
                                    <input type="radio" name="format_hasil_voting" value="{{ $value }}" class="mt-1" {{ $event->format_hasil_voting === $value ? 'checked' : '' }}>
                                    <span><strong class="block text-[12px]">{{ $label }}</strong><span class="text-[11px] text-[#73716c]">{{ $desc }}</span></span>
                                </span>
                                <span class="text-[11px] font-mono border border-[#e5e1db] rounded px-2 py-1 whitespace-nowrap ml-2">{{ $contoh }}</span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Rekening Settlement & Gateways --}}
                    {{-- <div class="bg-white border border-[#e3ded6] rounded-xl p-5">
                        <div class="flex items-start gap-3 mb-3">
                            <span class="text-xl">🏦</span>
                            <div>
                                <h2 class="font-bold text-[15px] m-0">Rekening Settlement &amp; Gateways</h2>
                                <p class="text-[11px] text-[#73716c] mt-1">
                                    Catatan internal saja &mdash; metode pembayaran &amp; settlement sungguhan dikonfigurasi
                                    lewat dashboard Midtrans, bukan dari sini.
                                </p>
                            </div>
                        </div>

                        <label class="block text-[11px] font-bold text-[#73716c] mb-2">Metode Pembayaran (catatan)</label>
                        @php $metodeAktif = $event->metode_pembayaran ?? []; @endphp
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            @foreach (['qris' => 'QRIS Instant', 'bri_va' => 'BRI VA'] as $key => $label)
                                <label class="border border-[#e5e1db] rounded-lg p-2.5 text-[12px] flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="metode_pembayaran[]" value="{{ $key }}" {{ in_array($key, $metodeAktif, true) ? 'checked' : '' }}>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>

                        <label class="block text-[11px] font-bold text-[#73716c] mb-2">Rekening Utama Penampungan (catatan)</label>
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <input type="text" name="rekening_bank" value="{{ $event->rekening_bank ?? '' }}"placeholder="Bank (contoh: BRI)"
                                   class="border border-[#e5e1db] rounded-lg px-3 py-2 text-[12px]">
                            <input type="text" name="rekening_nomor" value="{{ $event->rekening_nomor }}" placeholder="Nomor rekening"
                                   class="border border-[#e5e1db] rounded-lg px-3 py-2 text-[12px]">
                        </div>
                        <input type="text" name="rekening_atas_nama" value="{{ $event->rekening_atas_nama }}" placeholder="a.n. Pemilik rekening"
                               class="w-full border border-[#e5e1db] rounded-lg px-3 py-2 text-[12px]">
                    </div> --}}
                </div>

                <div class="mt-8 pt-4 border-t border-[#e3ded6] flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('admin.voting.settings', $event) }}"
                       class="w-full sm:w-auto px-4 py-2.5 text-[12px] font-semibold text-[#73716c] border border-[#e3ded6] hover:bg-gray-50 rounded-lg text-center transition">
                        Batalkan Perubahan
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto px-5 py-2.5 text-[12px] font-bold bg-gold hover:bg-[#a3721a] text-white rounded-lg shadow-sm transition whitespace-nowrap text-center">
                        ✓ Simpan &amp; Terapkan Pengaturan
                    </button>
                </div>
            </form>
        @endif
    </main>
</div>

<style>
    .toggle-switch { position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer; }
    .toggle-switch input { display: none; }
    .toggle-track { position: absolute; inset: 0; background: #e5e1db; border-radius: 999px; transition: background .15s; }
    .toggle-thumb { position: absolute; top: 3px; left: 3px; width: 18px; height: 18px; background: white; border-radius: 50%; box-shadow: 0 1px 2px rgba(0,0,0,.25); transition: left .15s; }
    .toggle-switch input:checked + .toggle-track { background: #ba8320; }
    .toggle-switch input:checked + .toggle-track .toggle-thumb { left: 23px; }
</style>

<script>
    document.querySelectorAll('.format-option').forEach(label => {
        label.addEventListener('click', () => {
            document.querySelectorAll('.format-option').forEach(l => l.classList.remove('border-gold', 'bg-[#fff8e9]'));
            label.classList.add('border-gold', 'bg-[#fff8e9]');
        });
    });

    function updateDurasi() {
        const mulai = document.getElementById('votingMulai')?.value;
        const selesai = document.getElementById('votingSelesai')?.value;
        const teks = document.getElementById('durasiTeks');
        if (!teks) return;

        if (!mulai || !selesai) {
            teks.textContent = '-';
            return;
        }

        const diffMs = new Date(selesai) - new Date(mulai);
        if (diffMs <= 0) {
            teks.textContent = 'Tanggal tidak valid';
            return;
        }

        const totalMinutes = Math.floor(diffMs / 60000);
        const hari = Math.floor(totalMinutes / 1440);
        const jam = Math.floor((totalMinutes % 1440) / 60);
        const menit = totalMinutes % 60;
        teks.textContent = `${hari} Hari ${jam} Jam ${menit} Mnt`;
    }

    document.getElementById('votingMulai')?.addEventListener('input', updateDurasi);
    document.getElementById('votingSelesai')?.addEventListener('input', updateDurasi);
    updateDurasi();
</script>
</body>
</html>