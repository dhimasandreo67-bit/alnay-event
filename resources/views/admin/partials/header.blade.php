@vite(['resources/css/app.css', 'resources/js/app.js'])
<header
    class="fixed top-0 right-0 left-[235px] z-[15] flex h-[76px] min-h-[76px] items-center justify-between border-b border-[#e8e8e5] bg-white px-[30px] font-sans text-[#1e2530] max-[900px]:left-[70px] max-[900px]:px-[18px]">
    @if (($variant ?? 'module') === 'dashboard')
        <div class="text-[11px] text-[#91959a]"><strong
                class="mt-2 block text-[17px] text-[#1e2530]">{{ $event?->nama ?? 'Verifikasi Pendaftaran Peserta' }}</strong>
        </div>
        <div class="flex items-center gap-[9px]"
            class="inline-flex items-center justify-center rounded-[5px] border px-[13px] py-[9px] text-[11px] font-bold text-white no-underline">

            <form method="POST" action="{{ route('admin.logout.web') }}">@csrf<button
                    class="inline-flex cursor-pointer items-center justify-center rounded-[5px] border border-[#dedbd4] bg-white px-[13px] py-[9px] text-[11px] font-medium text-[#56524c]"
                    type="submit">Keluar</button></form>
        </div>
    @elseif(($variant ?? 'module') === 'builder')
        <div class="flex min-w-0 flex-col gap-1 text-[11px] text-[#91959a]"><span
                class="text-[9px] text-[#ba8320]">Atelier Event › {{ $event?->nama ?? 'Pilih Event' }} › Atur Formulir
                Pendaftaran</span><strong class="truncate text-[17px] text-[#1e2530]">Formulir Pendaftaran —
                {{ $event?->nama ?? 'Event Baru' }}</strong><small class="text-[9px] text-[#91959a]">Atur data &
                persyaratan yang wajib diisi peserta fashion show busana adat</small></div>
        <div class="flex shrink-0 items-center gap-[9px]"><a
            class="inline-flex items-center justify-center whitespace-nowrap rounded-[5px] border border-[#dedbd4] bg-white px-[13px] py-[9px] text-[11px] font-medium text-[#56524c] no-underline"
            href="{{ $event ? route('public.registration', $event) : '#' }}" target="_blank">Lihat Form Publik</a><button
                class="inline-flex cursor-pointer items-center justify-center whitespace-nowrap rounded-[5px] border border-[#ba8320] bg-[#ba8320] px-[13px] py-[9px] text-[11px] font-bold text-white"
                type="button" id="saveFormHeader">▣ Simpan Formulir</button></div>
    @else
        <div class="text-[11px] text-[#91959a]">Admin Portal<strong
                class="mt-2 block text-[17px] text-[#1e2530]">{{ $event?->nama ?? 'Event Alnay' }}</strong></div>
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
</script>
