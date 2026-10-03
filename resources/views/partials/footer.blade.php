{{-- FOOTER — Global --}}
<footer class="bg-[#101010] text-white mt-16">
    <div class="mx-auto max-w-[1200px] px-5 py-12 md:px-8">

        {{-- BARIS 1: INFO UTAMA --}}
        <div class="grid grid-cols-1 gap-10 border-b border-[#2a2a2a] pb-10 md:grid-cols-[1.5fr_1fr_1fr_1.15fr]">

            {{-- Kolom 1: Brand + Deskripsi + Sosmed --}}
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/alnaylogo.png') }}" alt="ALNAY.ID"
                        class="h-9 w-9 object-contain">
                    <div>
                        <div class="font-bold tracking-[.12em]">ALNAY.ID</div>
                        <div class="text-[8px] tracking-[.15em] text-[#c9962e]">JAS &amp; GOWN BERBUDAYA</div>
                    </div>
                </div>
                <p class="mt-4 max-w-[330px] text-[10px] leading-5 text-[#858585]">
                    Platform penjualan dan penyewaan fashion etnik ALNAY.ID yang menghadirkan
                    busana adat Lampung dengan tampilan modern dan elegan.
                </p>

                {{-- Sosmed --}}
                <div class="mt-5 flex items-center gap-2">
                    <a href="https://www.instagram.com/alnay.id/" target="_blank" rel="noopener noreferrer"
                        aria-label="Instagram ALNAY.ID"
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-[#1a1a1a] hover:bg-[#E4405F] transition text-white">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                        </svg>
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61586314552607" target="_blank"
                        rel="noopener noreferrer" aria-label="Facebook ALNAY.ID"
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-[#1a1a1a] hover:bg-[#1877F2] transition text-white">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    <a href="https://www.tiktok.com/@alnay.id" target="_blank" rel="noopener noreferrer"
                        aria-label="TikTok ALNAY.ID"
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-[#1a1a1a] hover:bg-black transition text-white">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Kolom 2: Koleksi Adat --}}
            <div>
                <h3 class="text-[10px] font-semibold tracking-[.14em] text-white">KOLEKSI ADAT</h3>
                <ul class="mt-4 space-y-2 text-[10px] text-[#858585]">
                    <li>Jas Berbudaya</li>
                    <li>Gown Berbudaya</li>
                    <li>Set Baju Pasangan</li>
                    <li>Aksesoris Adat</li>
                    <li>Busana Lampung</li>
                </ul>
            </div>

            {{-- Kolom 3: Butuh Bantuan --}}
            <div>
                <h3 class="text-[10px] font-semibold tracking-[.14em] text-white">BUTUH BANTUAN?</h3>
                <div class="mt-4 space-y-2 text-[10px] text-[#858585]">
                    <p>Senin - Jumat: 08:00 - 20:00 WIB</p>
                    <p>Sabtu - Minggu: 09:00 - 18:00 WIB</p>
                    <p class="pt-1 leading-4">Lampung, Indonesia</p>
                </div>
            </div>

            {{-- Kolom 4: Layanan --}}
            <div>
                <h3 class="text-[10px] font-semibold tracking-[.14em] text-white">LAYANAN &amp; INFORMASI</h3>
                <ul class="mt-4 space-y-2 text-[10px] text-[#858585]">
                    <li>Cara &amp; Syarat Sewa Busana</li>
                    <li>Aturan Deposit</li>
                    <li>Jadwal Fitting Studio</li>
                    <li>Panduan Ukur Badan Online</li>
                    <li>Pemesanan Busana</li>
                </ul>
            </div>
        </div>

        {{-- BARIS 2: COPYRIGHT --}}
        <div class="flex flex-col gap-3 pt-5 text-[9px] text-[#626262] md:flex-row md:items-center md:justify-between">
            <div>© 2026 ALNAY.ID. All Rights Reserved.</div>
            <div class="flex flex-wrap gap-5">
                <span>Kebijakan Privasi</span>
                <span>Syarat &amp; Ketentuan</span>
                <span>Informasi ALNAY.ID</span>
            </div>
        </div>
    </div>
</footer>