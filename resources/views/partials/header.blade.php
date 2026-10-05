<header class="sticky top-0 z-50 border-b border-[#e9e9e9] bg-white/95 backdrop-blur">
    <div class="mx-auto flex h-[56px] max-w-[1200px] items-center justify-between px-5 md:px-8">
        <a href="{{ route('dashboard-user') }}"
            class="font-display text-[16px] font-bold tracking-tight text-[#13223a]">
            EVENT ALNAY.ID
        </a>

        @auth('pelanggan')
            @php
                $u = auth('pelanggan')->user();
                $fotoHeader = $u->foto_custom ? asset('storage/' . $u->foto_custom) : $u->foto_url;
                $inisialHeader = strtoupper(substr(trim($u->nama_custom ?: $u->nama), 0, 1));
            @endphp
            <a href="{{ route('profil-user') }}" aria-label="Profil pengguna"
                class="flex h-9 w-9 items-center justify-center rounded-full border border-[#d9d9d9] bg-white text-[#333] transition hover:bg-[#f7f7f7] overflow-hidden">
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