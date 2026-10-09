<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    header, .brand-font {
        font-family: 'Inter', sans-serif;
    }
</style>

<header class="sticky top-0 z-50 border-b border-[#e9e9e9] bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-[56px] max-w-[1200px] items-center justify-between px-5 md:px-8">
            <a href="{{ route('dashboard-user') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('images/alnaylogo.png') }}" alt="ALNAY.ID" class="h-9 w-9 object-contain">
                <span class="brand-font text-[16px] font-bold tracking-tight text-[#13223a]">
                    EVENT ALNAY.ID
                </span>
            </a>

        @auth('pelanggan')
            @php
                $u = auth('pelanggan')->user();
                $fotoHeader = $u->foto_custom ? asset('storage/' . $u->foto_custom) : $u->foto_url;
                $inisialHeader = strtoupper(substr(trim($u->nama_custom ?: $u->nama), 0, 1));
            @endphp
            <a href="{{ route('profil-user') }}" aria-label="Profil pengguna"
                class="flex h-9 w-9 items-center justify-center rounded-full border border-[#c99a32] bg-[#fbf6e9] text-[#c99a32] transition hover:bg-[#f7f0dc] overflow-hidden">
                @if ($fotoHeader)
                    <img src="{{ $fotoHeader }}" alt="{{ $u->nama_custom ?: $u->nama }}"
                        class="w-full h-full object-cover">
                @else
                    <span class="text-[11px] font-bold">{{ $inisialHeader }}</span>
                @endif
            </a>
        @else
            <a href="{{ route('auth.google') }}"
                class="rounded-full bg-[#cf9b32] px-4 py-2 text-[11px] font-semibold text-white transition hover:bg-[#b88625]">
                Login
            </a>
        @endauth
    </div>
</header>