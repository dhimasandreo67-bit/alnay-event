<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan Event & Voting | ALNAY.ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])

    {{-- ✅ WAJIB: agar toast & modal berfungsi --}}
    @include('admin.partials.pwa-head')
</head>

<body class="bg-cream font-['Inter'] text-[13px] text-[#22201d]">
    <div class="flex min-h-screen">
        @include('admin.partials.sidebar', ['active' => 'voting-settings', 'event' => $event])

        <main class="flex-1 min-w-0 p-4 sm:p-6 pb-24 ml-0 md:ml-[235px]">
            <h1 class="text-xl sm:text-2xl font-bold m-0">Pengaturan Event & Voting</h1>
            <p class="text-[#73716c] text-[12px] mt-1.5 max-w-2xl mb-6">
                Konfigurasi status publikasi, periode voting, format tampilan hasil, dan catatan rekening settlement.
            </p>

            {{-- Flash message --}}
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-emerald-50 text-emerald-700 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (!$event)
                <div class="bg-white border border-dashed border-[#e3ded6] rounded-xl p-8 text-center text-[#98958f]">
                    Belum ada event. Buat event dulu di halaman "Buat Event Baru".
                </div>
            @else
                <form method="POST" action="{{ route('admin.voting.settings.update', $event) }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">

                        {{-- STATUS VOTING --}}
                        <div class="bg-white border border-[#e3ded6] rounded-xl p-5 flex flex-col">
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div>
                                    <h2 class="font-bold text-[15px] m-0">Status Voting</h2>
                                    <p class="text-[11px] text-[#73716c] mt-1 leading-relaxed">
                                        Aktifkan untuk membuka voting. Nonaktifkan untuk menutup voting — pengunjung tetap bisa lihat kandidat & hasil.
                                    </p>
                                </div>
                                <label class="toggle-switch shrink-0">
                                    {{-- Hidden input untuk voting_aktif --}}
                                    <input type="hidden" name="voting_aktif" value="0">
                                    <input type="checkbox" id="toggleVotingSwitch" name="voting_aktif" value="1"
                                        {{ $event->voting_aktif ? 'checked' : '' }}>
                                    <span class="toggle-track"><span class="toggle-thumb"></span></span>
                                </label>
                            </div>

                            {{-- Status indicator --}}
                            @if ($event->voting_aktif)
                                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3.5 flex items-center justify-between gap-3 text-[12px] mt-3">
                                    <div>
                                        <strong class="block text-emerald-800">✅ Voting Aktif</strong>
                                        <span class="text-emerald-700">Pengunjung bisa vote kandidat sekarang.</span>
                                    </div>
                                    <span class="bg-emerald-500 text-white text-[10px] font-bold px-2 py-1 rounded whitespace-nowrap shrink-0">AKTIF</span>
                                </div>
                            @else
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3.5 flex items-center justify-between gap-3 text-[12px] mt-3">
                                    <div>
                                        <strong class="block text-gray-700">⏸️ Voting Ditutup</strong>
                                        <span class="text-gray-500">Pengunjung tidak bisa vote — tapi bisa lihat kandidat.</span>
                                    </div>
                                    <span class="bg-gray-500 text-white text-[10px] font-bold px-2 py-1 rounded whitespace-nowrap shrink-0">DITUTUP</span>
                                </div>
                            @endif
                        </div>

                        {{-- Periode Waktu & Countdown --}}
                        <div class="bg-white border border-[#e3ded6] rounded-xl p-5">
                            <h2 class="font-bold text-[15px] m-0">Periode Waktu & Countdown</h2>
                            <p class="text-[11px] text-[#73716c] mt-1 mb-4 leading-relaxed">Jadwal pembukaan dan penutupan otomatis sistem voting.</p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-[#73716c] mb-1">Mulai Pemilihan (Kick-off)</label>
                                    <input type="datetime-local" id="votingMulai" name="voting_mulai"
                                        value="{{ optional($event->voting_mulai)->format('Y-m-d\TH:i') }}"
                                        class="w-full border border-[#e5e1db] rounded-lg px-3 py-2 text-[12px]">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-[#73716c] mb-1">Batas Akhir Voting (Penutupan)</label>
                                    <input type="datetime-local" id="votingSelesai" name="voting_selesai"
                                        value="{{ optional($event->voting_selesai)->format('Y-m-d\TH:i') }}"
                                        class="w-full border border-[#e5e1db] rounded-lg px-3 py-2 text-[12px]">
                                </div>
                            </div>

                            <div id="durasiInfo" class="bg-[#f7f5f0] rounded-lg p-2.5 text-[11px] flex items-center justify-between gap-2 mb-4">
                                <span>Durasi Aktif: <strong id="durasiTeks">-</strong></span>
                                <span class="text-[#98958f] shrink-0">WIB (GMT+7)</span>
                            </div>

                            <div class="space-y-3">
                                {{-- Auto Freeze --}}
                                <label class="flex items-start gap-2 text-[12px]">
                                    <input type="hidden" name="auto_freeze_voting" value="0">
                                    <input type="checkbox" name="auto_freeze_voting" value="1" class="mt-0.5 shrink-0" {{ $event->auto_freeze_voting ? 'checked' : '' }}>
                                    <span>
                                        <strong class="block">Kunci Suara Otomatis (Auto Freeze)</strong>
                                        <span class="text-[#73716c] text-[11px] leading-relaxed">Tutup perolehan secara sistem tepat saat tenggat waktu tercapai tanpa jeda.</span>
                                    </span>
                                </label>

                                {{-- ✅ TAMBAHAN: Tampilkan Hasil Voting ke Publik --}}
                                <label class="flex items-start gap-2 text-[12px]">
                                    <input type="hidden" name="tampilkan_hasil_voting" value="0">
                                    <input type="checkbox" name="tampilkan_hasil_voting" value="1" class="mt-0.5 shrink-0" {{ $event->tampilkan_hasil_voting ? 'checked' : '' }}>
                                    <span>
                                        <strong class="block">Tampilkan Hasil Voting ke Publik</strong>
                                        <span class="text-[#73716c] text-[11px] leading-relaxed">Jika dimatikan, statistik suara disembunyikan dari pengunjung (meskipun voting aktif).</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Format Tampilan Hasil Vote --}}
                        <div class="bg-white border border-[#e3ded6] rounded-xl p-5 md:col-span-2">
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <h2 class="font-bold text-[15px] m-0">Format Tampilan Hasil Vote</h2>
                                <span class="bg-white border border-gold text-gold text-[10px] font-bold px-2 py-1 rounded whitespace-nowrap shrink-0">TAMPILAN PUBLIK</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach ([
                                    'angka' => ['Angka Vote Asli', 'Menampilkan jumlah nominal suara terkumpul riil', '1.420 Suara'],
                                    'persentase' => ['Persentase (%)', 'Menampilkan persentase proporsional dari total suara masuk', '34,8%'],
                                    'keduanya' => ['Tampilkan Keduanya', 'Menampilkan angka riil diikuti persentase', '1.420 (34,8%)'],
                                ] as $value => [$label, $desc, $contoh])
                                    <label class="flex flex-col gap-2.5 border rounded-lg p-3.5 cursor-pointer format-option {{ $event->format_hasil_voting === $value ? 'border-gold bg-[#fff8e9]' : 'border-[#e5e1db]' }}">
                                        <span class="flex items-start gap-2">
                                            <input type="radio" name="format_hasil_voting" value="{{ $value }}" class="mt-0.5 shrink-0" {{ $event->format_hasil_voting === $value ? 'checked' : '' }}>
                                            <span>
                                                <strong class="block text-[12px]">{{ $label }}</strong>
                                                <span class="text-[11px] text-[#73716c] leading-relaxed">{{ $desc }}</span>
                                            </span>
                                        </span>
                                        <span class="text-[11px] font-mono border border-[#e5e1db] rounded px-2 py-1 bg-[#faf8f4] text-center">{{ $contoh }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-4 border-t border-[#e3ded6] flex flex-wrap items-center justify-end gap-3 pb-1">
                        <a href="{{ route('admin.voting.settings', $event) }}"
                           class="w-full sm:w-auto px-4 py-2.5 text-[12px] font-semibold text-[#73716c] border border-[#e3ded6] hover:bg-gray-50 rounded-lg text-center transition">
                            Batalkan Perubahan
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto px-5 py-2.5 text-[12px] font-bold bg-gold hover:bg-[#a3721a] text-white rounded-lg shadow-sm transition whitespace-nowrap text-center">
                            ✓ Simpan & Terapkan Pengaturan
                        </button>
                    </div>
                </form>
            @endif
        </main>
    </div>

    <style>
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            cursor: pointer;
        }
        .toggle-switch input { display: none; }
        .toggle-track {
            position: absolute;
            inset: 0;
            background: #e5e1db;
            border-radius: 999px;
            transition: background .15s;
        }
        .toggle-thumb {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .25);
            transition: left .15s;
        }
        .toggle-switch input:checked + .toggle-track { background: #ba8320; }
        .toggle-switch input:checked + .toggle-track .toggle-thumb { left: 23px; }
    </style>

    <script>
    // ---- Format hasil voting (radio) ----
    document.querySelectorAll('.format-option').forEach(label => {
        label.addEventListener('click', () => {
            document.querySelectorAll('.format-option').forEach(l =>
                l.classList.remove('border-gold', 'bg-[#fff8e9]'));
            label.classList.add('border-gold', 'bg-[#fff8e9]');
        });
    });

    // ---- Hitung durasi ----
    function updateDurasi() {
        const mulai   = document.getElementById('votingMulai')?.value;
        const selesai = document.getElementById('votingSelesai')?.value;
        const teks    = document.getElementById('durasiTeks');
        if (!teks) return;

        if (!mulai || !selesai) { teks.textContent = '-'; return; }

        const diffMs = new Date(selesai) - new Date(mulai);
        if (diffMs <= 0) { teks.textContent = 'Tanggal tidak valid'; return; }

        const totalMinutes = Math.floor(diffMs / 60000);
        const hari  = Math.floor(totalMinutes / 1440);
        const jam   = Math.floor((totalMinutes % 1440) / 60);
        const menit = totalMinutes % 60;
        teks.textContent = `${hari} Hari ${jam} Jam ${menit} Mnt`;
    }
    document.getElementById('votingMulai')?.addEventListener('input', updateDurasi);
    document.getElementById('votingSelesai')?.addEventListener('input', updateDurasi);
    updateDurasi();

    // ---- ✅ AUTO-SAVE saat toggle voting diubah ----
    const toggleVoting = document.getElementById('toggleVotingSwitch');
    if (toggleVoting) {
        toggleVoting.addEventListener('change', function () {
            const form = this.closest('form');
            if (!form) return;

            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                form.submit();
            }
        });
    }
</script>
</body>
</html>