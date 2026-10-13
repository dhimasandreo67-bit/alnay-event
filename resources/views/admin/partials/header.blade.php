@vite(['resources/css/app.css', 'resources/js/app.js'])
@include('admin.partials.pwa-head')
<header
    class="fixed top-0 right-0 left-[235px] z-[15] flex h-[76px] min-h-[76px] items-center justify-between border-b border-[#e8e8e5] bg-white px-[30px] font-sans text-[#1e2530] max-[900px]:left-[70px] max-[900px]:px-[18px]">

    @if (($variant ?? 'module') === 'dashboard')
        <div class="text-[11px] text-[#91959a]">
            <strong
                class="mt-2 block text-[17px] text-[#1e2530]">{{ $event?->nama ?? 'Verifikasi Pendaftaran Peserta' }}</strong>
        </div>
        <div class="flex items-center gap-[9px]">
            <form method="POST" action="{{ route('admin.logout.web') }}">
                @csrf
                <button
                    class="inline-flex cursor-pointer items-center justify-center rounded-[5px] border border-[#dedbd4] bg-white px-[13px] py-[9px] text-[11px] font-medium text-[#56524c]"
                    type="submit">Keluar</button>
            </form>
        </div>
    @elseif(($variant ?? 'module') === 'builder')
        <div class="flex min-w-0 flex-col gap-1 text-[11px] text-[#91959a]">
            <span class="text-[9px] text-[#ba8320]">Atelier Event › {{ $event?->nama ?? 'Pilih Event' }} › Atur Formulir
                Pendaftaran</span>
            <strong class="truncate text-[17px] text-[#1e2530]">Formulir Pendaftaran 
                {{ $event?->nama ?? 'Event Baru' }}</strong>
            <small class="text-[9px] text-[#91959a]">Atur data & persyaratan yang wajib diisi peserta fashion show
                busana adat</small>
        </div>

        <div class="flex shrink-0 items-center gap-3">

            {{-- TOGGLE PENDAFTARAN — BISA DIKLIK --}}
            @if ($event)
                <button type="button" onclick="togglePendaftaran({{ $event->id }})" id="togglePendaftaranBtn"
                    data-event-id="{{ $event->id }}"
                    title="Klik untuk {{ $event->pendaftaran_dibuka ? 'nonaktifkan' : 'aktifkan' }} pendaftaran"
                    class="group inline-flex items-center gap-2 px-3 py-[9px] text-[11px] font-bold rounded-[5px] border transition-all duration-200 cursor-pointer shadow-sm hover:shadow-md
                        {{ $event->pendaftaran_dibuka
                            ? 'bg-emerald-50 text-emerald-700 border-emerald-300 hover:bg-emerald-100 hover:border-emerald-400'
                            : 'bg-rose-50 text-rose-700 border-rose-300 hover:bg-rose-100 hover:border-rose-400' }}">

                    {{-- Toggle Switch --}}
                    <span id="togglePendaftaranTrack"
                        style="
                            display: inline-block;
                            position: relative;
                            height: 16px;
                            width: 30px;
                            border-radius: 9999px;
                            transition: background-color 0.25s ease;
                            background-color: {{ $event->pendaftaran_dibuka ? '#10b981' : '#fb7185' }};
                            flex-shrink: 0;
                        ">
                        <span id="togglePendaftaranThumb"
                            style="
                                position: absolute;
                                top: 2px;
                                left: 2px;
                                height: 12px;
                                width: 12px;
                                background-color: #ffffff;
                                border-radius: 9999px;
                                box-shadow: 0 1px 2px rgba(0,0,0,0.2);
                                transition: transform 0.25s ease;
                                transform: translateX({{ $event->pendaftaran_dibuka ? '14px' : '0' }});
                            "></span>
                    </span>

                    {{-- Label dinamis --}}
                    <span id="togglePendaftaranLabel" class="text-left whitespace-nowrap">
                        {{ $event->pendaftaran_dibuka ? 'Nonaktifkan Pendaftaran' : 'Aktifkan Pendaftaran' }}
                    </span>
                </button>
            @endif

            <button type="button" onclick="saveThenPreview()"
                class="inline-flex cursor-pointer items-center justify-center whitespace-nowrap rounded-[5px] border border-[#dedbd4] bg-white px-3 py-[9px] text-[11px] font-bold text-[#56524c] hover:bg-[#f9f8f5] transition">
                Lihat Form Publik
            </button>
            <button
                class="inline-flex cursor-pointer items-center justify-center whitespace-nowrap rounded-[5px] border border-[#ba8320] bg-[#ba8320] px-3 py-[9px] text-[11px] font-bold text-white hover:bg-[#a06f1a] transition"
                type="button" id="saveFormHeader">▣ Simpan Formulir</button>
        </div>
    @else
        <div class="text-[11px] text-[#91959a]">
            Admin Portal
            <strong class="mt-2 block text-[17px] text-[#1e2530]">{{ $event?->nama ?? 'Event Alnay' }}</strong>
        </div>
        <a class="inline-flex items-center justify-center whitespace-nowrap rounded-[5px] border border-[#dedbd4] bg-white px-[13px] py-[9px] text-[11px] font-medium text-[#56524c] no-underline"
            href="{{ route('admin.dashboard') }}">Dashboard</a>
    @endif
</header>

<div id="adminToast"
    class="pointer-events-none fixed right-5 top-5 z-[60] translate-x-[120%] rounded-lg border border-[#d9d2c8] bg-[#24211d] px-4 py-3 text-xs font-medium text-white opacity-0 shadow-lg transition duration-300"
    role="status" aria-live="polite"></div>

<script>
window.showAdminToast = function(message, type = 'success') {
    const toast = document.getElementById('adminToast');
    if (!toast) return;

    toast.textContent = message;
    toast.classList.toggle('bg-[#b94b3d]', type === 'error');
    toast.classList.toggle('bg-[#24211d]', type !== 'error');
    toast.classList.remove('translate-x-[120%]', 'opacity-0');
    toast.classList.add('translate-x-0', 'opacity-100');

    window.clearTimeout(window.adminToastTimer);
    window.adminToastTimer = window.setTimeout(() => {
        toast.classList.remove('translate-x-0', 'opacity-100');
        toast.classList.add('translate-x-[120%]', 'opacity-0');
    }, 2800);
};

window.togglePendaftaran = async function(eventId) {
    const btn = document.getElementById('togglePendaftaranBtn');
    const label = document.getElementById('togglePendaftaranLabel');
    const track = document.getElementById('togglePendaftaranTrack');
    const thumb = document.getElementById('togglePendaftaranThumb');
    if (!btn) return;

    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-wait');

    try {
        const response = await fetch(`/admin/events/${eventId}/toggle-pendaftaran`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Gagal mengubah status.');

        const isOn = data.pendaftaran_dibuka;

        if (isOn) {
            btn.classList.remove('bg-rose-50', 'text-rose-700', 'border-rose-300', 'hover:bg-rose-100', 'hover:border-rose-400');
            btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300', 'hover:bg-emerald-100', 'hover:border-emerald-400');
            if (label) label.textContent = 'Nonaktifkan Pendaftaran';
            if (track) track.style.backgroundColor = '#10b981';
            if (thumb) thumb.style.transform = 'translateX(14px)';
        } else {
            btn.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-300', 'hover:bg-emerald-100', 'hover:border-emerald-400');
            btn.classList.add('bg-rose-50', 'text-rose-700', 'border-rose-300', 'hover:bg-rose-100', 'hover:border-rose-400');
            if (label) label.textContent = 'Aktifkan Pendaftaran';
            if (track) track.style.backgroundColor = '#fb7185';
            if (thumb) thumb.style.transform = 'translateX(0)';
        }

        if (typeof window.showAdminToast === 'function') {
            window.showAdminToast(data.message, 'success');
        }
    } catch (error) {
        if (typeof window.showAdminToast === 'function') {
            window.showAdminToast(error.message, 'error');
        } else {
            alert(error.message);
        }
    } finally {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-wait');
    }
};

window.saveThenPreview = function() {
    @if ($event)
    window.open('{{ route('admin.preview-form', $event->id) }}', '_blank');
    @endif
};
</script>
