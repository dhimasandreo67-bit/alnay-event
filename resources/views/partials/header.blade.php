{{-- Favicon Global (Aman di semua halaman) --}}
<link rel="icon" type="image/png" href="{{ asset('images/alnaylogo.png') }}">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .brand-font {
        font-family: 'Inter', sans-serif;
    }

    /* Header menempel di atas layar, jadi link anchor (#faq, #alur-pendaftaran, dst)
       harus berhenti sedikit di bawah header supaya judulnya tidak tertutup. */
    html {
        scroll-padding-top: 76px;
    }
</style>

{{-- Header Global
     Memakai position: fixed (bukan sticky) supaya TETAP menempel saat di-scroll
     walaupun halaman punya overflow-x: hidden di html/body atau pembungkus lain.
     Spacer di bawahnya menggantikan ruang yang tadinya dipakai header di alur halaman. --}}
<header class="fixed inset-x-0 top-0 z-50 w-full border-b border-gray-200/60 bg-white/80 backdrop-blur-md shadow-sm transition-all duration-300">
    <div class="mx-auto flex h-[64px] max-w-[1200px] items-center justify-between px-5 md:px-8">
        
        {{-- LOGO & BRAND NAME --}}
        <a href="{{ route('dashboard-user') }}" class="group flex items-center gap-3 transition-transform duration-300 hover:scale-[1.02]">
            <img src="{{ asset('images/alnaylogo.png') }}" alt="ALNAY.ID" 
                 class="h-9 w-9 object-contain transition-transform duration-300 group-hover:rotate-6 group-hover:scale-110 drop-shadow-sm">
            
            <span class="brand-font text-[17px] font-extrabold tracking-tight text-[#13223a] transition-colors duration-300 group-hover:text-[#c99a32]">
                EVENT ALNAY.ID
            </span>
        </a>

        {{-- AUTH SECTION --}}
        @auth('pelanggan')
            @php
                $u = auth('pelanggan')->user();
                $fotoHeader = $u->foto_custom ? asset('storage/' . $u->foto_custom) : $u->foto_url;
                $inisialHeader = strtoupper(substr(trim($u->nama_custom ?: $u->nama), 0, 1));
            @endphp
            
            <a href="{{ route('profil-user') }}" aria-label="Profil pengguna"
                class="group relative flex h-9 w-9 items-center justify-center rounded-full border border-[#c99a32]/40 bg-[#fbf6e9] text-[#c99a32] transition-all duration-300 hover:border-[#c99a32] hover:bg-[#c99a32] hover:text-white hover:shadow-md hover:shadow-[#c99a32]/30 hover:scale-105 overflow-hidden">
                @if ($fotoHeader)
                    <img src="{{ $fotoHeader }}" alt="{{ $u->nama_custom ?: $u->nama }}"
                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110">
                @else
                    <span class="text-[12px] font-bold transition-colors duration-300 group-hover:text-white">
                        {{ $inisialHeader }}
                    </span>
                @endif
            </a>
        @else
            <a href="{{ route('auth.google') }}"
                class="rounded-full bg-[#cf9b32] px-5 py-2.5 text-[12px] font-bold text-white shadow-sm shadow-[#cf9b32]/20 transition-all duration-300 hover:bg-[#b88625] hover:shadow-lg hover:shadow-[#cf9b32]/40 hover:scale-105 active:scale-95">
                Login
            </a>
        @endauth
    </div>
</header>

{{-- Spacer setinggi header (64px) supaya konten tidak tertutup header yang fixed --}}
<div class="h-[64px]" aria-hidden="true"></div>