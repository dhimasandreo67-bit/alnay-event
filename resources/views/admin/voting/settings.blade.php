<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    @include('admin.partials.pwa-head')
    <title>Pengaturan Event &amp; Voting | ALNAY.ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
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
                    <div class="bg-white rounded-xl border border-slate-200 p-4 md:p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex-1 pr-3">
                                <h3 class="font-semibold text-slate-800 flex items-center gap-2 text-sm md:text-base">
                                    Visibilitas & Status Publikasi
                                </h3>
                                <p class="text-xs text-slate-500 mt-1">Tentukan ketersediaan event untuk pengguna umum.</p>
                            </div>
                        </div>

                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 md:p-4">
                            <label class="flex items-center justify-between cursor-pointer gap-3">
                                <div class="flex-1">
                                    <p class="font-medium text-slate-800 text-sm md:text-base leading-tight">Event Aktif & Siap Menerima Suara</p>
                                    <p class="text-xs text-slate-600 mt-1">Terbuka untuk masyarakat umum melalui landing page portal resmi.</p>
                                </div>
                                
                                {{-- Toggle Switch yang Lebih Besar untuk Mobile --}}
                                <div class="relative flex-shrink-0">
                                    <input type="hidden" name="voting_aktif" value="0">
                                    <input type="checkbox" name="voting_aktif" value="1" 
                                        {{ $event->voting_aktif ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="w-14 h-8 md:w-11 md:h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-7 after:w-7 md:after:h-5 md:after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                                </div>
                            </label>
                            
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-xs text-slate-600">Status saat ini:</span>
                                <span class="text-xs font-bold px-2 py-1 rounded {{ $event->voting_aktif ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $event->voting_aktif ? 'PUBLIK' : 'PRIVAT' }}
                                </span>
                            </div>
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

                        {{-- Auto Freeze --}}
                        <label class="flex items-start gap-3 p-3 bg-slate-50 rounded-lg cursor-pointer">
                            <div class="relative flex-shrink-0 mt-0.5">
                                <input type="hidden" name="auto_freeze_voting" value="0">
                                <input type="checkbox" name="auto_freeze_voting" value="1"
                                    {{ $event->auto_freeze_voting ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-14 h-8 md:w-11 md:h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-7 after:w-7 md:after:h-5 md:after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-sm text-slate-800">Kunci Suara Otomatis (Auto Freeze)</p>
                                <p class="text-xs text-slate-600 mt-0.5">Tutup perolehan secara sistem tepat saat tenggat waktu tercapai tanpa jeda.</p>
                            </div>
                        </label>

                        {{-- Blackout H-2 --}}
                        <label class="flex items-start gap-3 p-3 bg-slate-50 rounded-lg cursor-pointer">
                            <div class="relative flex-shrink-0 mt-0.5">
                                <input type="hidden" name="blackout_h2_voting" value="0">
                                <input type="checkbox" name="blackout_h2_voting" value="1"
                                    {{ $event->blackout_h2_voting ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-14 h-8 md:w-11 md:h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-7 after:w-7 md:after:h-5 md:after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-sm text-slate-800">Mode Rahasia H-2 (Blackout Leaderboard)</p>
                                <p class="text-xs text-slate-600 mt-0.5">Sembunyikan total suara publik 48 jam sebelum grand final demi unsur kejutan.</p>
                            </div>
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

                <div class="mt-8 pt-4 border-t border-[#e3ded6] flex flex-wrap items-center justify-end gap-3 pb-1">
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