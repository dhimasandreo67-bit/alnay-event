<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Peserta Tampil | ALNAY.ID</title>
    @vite(['resources/css/app.css'])
    @include('admin.partials.pwa-head')
</head>
<body class="bg-[#f7f6f3] text-[#24211d] font-sans">
    <div class="flex min-h-screen">
        @include('admin.partials.sidebar', ['active' => 'peserta-tampil', 'event' => $event])

        <main class="flex-1 min-w-0 ml-[235px] mt-[80px]">
            @include('admin.partials.header', ['variant' => 'dashboard', 'event' => $event])

            <div class="p-6 md:p-8 space-y-6">

                {{-- HEADER --}}
                <div class="bg-white rounded-xl border border-[#e7e2da] p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-[#24211d]">Kelola Peserta Tampil</h1>
                            <p class="text-sm text-[#8c8982] mt-1">
                                Atur peserta mana yang tampil di halaman publik & bisa divote.
                            </p>
                            @if ($event)
                                <p class="text-xs text-[#8c8982] mt-2">
                                    Event: <strong>{{ $event->nama }}</strong>
                                </p>
                            @endif
                        </div>

                        @if ($event)
                            <div class="flex items-center gap-2 flex-wrap">
                                <form action="{{ route('admin.peserta-tampil.toggle-semua', $event->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="tampil" value="1">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-lg transition">
                                        Tampilkan Semua
                                    </button>
                                </form>
                                <form action="{{ route('admin.peserta-tampil.toggle-semua', $event->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="tampil" value="0">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-semibold text-sm rounded-lg transition">
                                        Sembunyikan Semua
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- FLASH --}}
                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- LIST PESERTA --}}
                @if ($event)
                    <div class="bg-white rounded-xl border border-[#e7e2da] overflow-hidden">
                        <div class="p-4 border-b border-[#e7e2da]">
                            <h3 class="text-sm font-semibold text-[#24211d]">
                                Daftar Peserta ({{ $pesertas->count() }})
                            </h3>
                        </div>

                        @if ($pesertas->count() > 0)
                            <div class="divide-y divide-[#e7e2da]">
                                @foreach ($pesertas as $p)
                                    <div class="p-4 flex items-center justify-between gap-4 hover:bg-[#faf8f4] transition">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 rounded-full bg-[#C28E27]/10 text-[#C28E27] flex items-center justify-center font-bold text-sm">
                                                {{ strtoupper(substr($p->namaTampil(), 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold text-[#24211d] truncate">{{ $p->namaTampil() }}</p>
                                                <p class="text-xs text-[#8c8982] truncate">{{ $p->emailTampil() }}</p>
                                                <p class="text-xs text-[#8c8982] mt-0.5">
                                                    Registrasi:
                                                    <span class="font-semibold
                                                        {{ $p->status_registrasi === 'lunas' ? 'text-emerald-600' : ($p->status_registrasi === 'dp' ? 'text-amber-600' : 'text-rose-500') }}">
                                                        {{ ucfirst($p->status_registrasi ?? 'belum') }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>

                                        {{-- TOGGLE --}}
                                        <form action="{{ route('admin.peserta-tampil.toggle', $p->id) }}" method="POST"
                                            class="toggle-tampil-form shrink-0">
                                            @csrf
                                            <button type="submit"
                                                class="toggle-tampil-btn px-4 py-2 text-xs font-bold rounded-lg transition
                                                    {{ $p->tampil_di_publik
                                                        ? 'bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200'
                                                        : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-200' }}">
                                                {{ $p->tampil_di_publik ? 'Tampil di Publik' : 'Disembunyikan' }}
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-12 text-center text-[#8c8982]">
                                <p class="text-sm">Belum ada peserta diterima di event ini.</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-[#e7e2da] p-8 text-center text-[#8c8982]">
                        Pilih event dulu di halaman lain.
                    </div>
                @endif

            </div>
        </main>
    </div>

    {{-- SCRIPT — AJAX toggle --}}
    <script>
        document.querySelectorAll('.toggle-tampil-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const btn = form.querySelector('.toggle-tampil-btn');
                if (!btn) return;

                const originalHTML = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = 'Memproses...';

                try {
                    const token = form.querySelector('input[name="_token"]').value;
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form),
                    });

                    const data = await res.json();
                    if (!res.ok || !data.success) throw new Error(data.message || 'Gagal.');

                                        // Inline style — biar nggak tergantung Tailwind
                    btn.style.background = '';
                    btn.style.color = '';
                    btn.style.borderColor = '';

                    if (data.tampilDiPublik) {
                        btn.style.background = '#eff6ff';   // bg-blue-50
                        btn.style.color = '#1d4ed8';        // text-blue-700
                        btn.style.borderColor = '#bfdbfe';  // border-blue-200
                        btn.innerHTML = 'Tampil di Publik';
                    } else {
                        btn.style.background = '#f3f4f6';   // bg-gray-100
                        btn.style.color = '#4b5563';        // text-gray-600
                        btn.style.borderColor = '#e5e7eb';  // border-gray-200
                        btn.innerHTML = 'Disembunyikan';
                    }
                } catch (err) {
                    btn.innerHTML = originalHTML;
                    alert('Gagal: ' + err.message);
                } finally {
                    btn.disabled = false;
                }
            });
        });
    </script>
</body>
</html>