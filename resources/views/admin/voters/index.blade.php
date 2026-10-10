<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('admin.partials.pwa-head')
    <title>Daftar Voter | ALNAY.ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-cream font-['Inter'] text-[13px] text-[#22201d]">
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['active' => 'voters', 'event' => $event])

    <main class="flex-1 min-w-0 p-6 ml-[235px]">
        <div class="flex items-center gap-3 mb-1">
            <h1 class="text-2xl font-bold m-0">Daftar Voter</h1>
            <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-500 text-[11px] font-bold px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span> Live
            </span>
        </div>
        <div class="flex items-center justify-between mb-5">
        <p class="text-[#73716c] text-[12px]">
            {{ $event?->nama ?? 'Belum ada event' }}
        </p>
        @if ($event)
        <div class="flex items-center justify-end gap-2">
            <button type="button" onclick="location.reload()" 
                class="px-4 py-2.5 bg-white border border-[#e3ded6] text-[12px] font-bold text-slate-700 rounded-lg hover:bg-slate-50 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>

            <button type="button" onclick="openManualVoteModal()" 
                class="px-4 py-2.5 bg-gold text-white text-[12px] font-bold rounded-lg hover:bg-[#a06f1a] transition flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Vote Manual
            </button>
        </div>
        @endif
    </div>

        @if (!$event)
            <div class="bg-white border border-dashed border-[#e3ded6] rounded-xl p-8 text-center text-[#98958f]">
                Belum ada event.
            </div>
        @else
            {{-- Stat cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-3xl font-bold">{{ number_format($totalSuara, 0, ',', '.') }}</div>
                    <div class="text-[11px] text-[#73716c] mt-1">Total Suara Masuk</div>
                </div>
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-3xl font-bold">{{ $voterUnik }}</div>
                    <div class="text-[11px] text-[#73716c] mt-1">Voter Unik (Akun Berbeda)</div>
                </div>
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-3xl font-bold text-green-600">
                        Rp {{ number_format($totalSuara * ($event->harga_per_vote ?? 0), 0, ',', '.') }}
                    </div>
                    <div class="text-[11px] text-[#73716c] mt-1">Total Pendapatan Vote</div>
                    <div class="text-[10px] text-[#98958f]">
                        {{ $totalSuara }} vote × Rp {{ number_format($event->harga_per_vote ?? 0, 0, ',', '.') }}
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                @if ($suaraTerakhir)
                    <div class="text-2xl font-bold font-mono text-slate-800">
                        {{ \Carbon\Carbon::parse($suaraTerakhir->tanggal_vote)->format('H.i.s') }}
                    </div>
                    <div class="text-[10px] text-[#98958f] mt-0.5">
                        {{ \Carbon\Carbon::parse($suaraTerakhir->tanggal_vote)->format('d M Y') }} WIB
                    </div>
                @else
                    <div class="text-2xl font-bold font-mono text-slate-400">-</div>
                @endif
                <div class="text-[11px] text-[#73716c] mt-1">Suara Terakhir Masuk</div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="flex items-center gap-3 mb-4">
                <select id="filterPeserta" class="border border-[#e3ded6] rounded-lg px-3 py-2.5 text-[12px] bg-white">
                    <option value="">Semua Peserta</option>
                    @foreach ($pesertaList as $p)
                        <option value="{{ $p->id }}">{{ $p->user->nama ?? '-' }}</option>
                    @endforeach
                </select>
                <input id="searchVoter" type="text" placeholder="Cari nama atau email voter..."
                       class="flex-1 border border-[#e3ded6] rounded-lg px-3 py-2.5 text-[12px]">
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl border border-[#e3ded6] overflow-hidden overflow-x-auto">
                <table class="w-full text-[12px]">
                    <thead>
                        <tr class="border-b border-[#e3ded6] text-left text-[10px] uppercase text-[#98958f]">
                            <th class="p-3 font-bold">Waktu Vote</th>
                            <th class="p-3 font-bold">Nama Voter</th>
                            <th class="p-3 font-bold">Email</th>
                            <th class="p-3 font-bold">Peserta yang Dipilih</th>
                            <th class="p-3 font-bold text-right">Jumlah Vote</th>
                            <th class="p-3 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="voterRows">
                        @forelse ($votings as $v)
                            @php
                                // 1. Definisikan variabel di sini agar aman digunakan di seluruh baris ini
                                $namaPeserta = $v->peserta?->user?->nama ?? 'Peserta Tidak Diketahui';
                                $inisial = collect(explode(' ', $namaPeserta))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');
                                
                                $namaVoter = $v->nama_voter ?? ($v->voter?->nama ?? 'Seseorang');
                                $emailVoter = $v->email_voter ?? ($v->voter?->email ?? '-');
                                
                                // ✅ Escape tanda kutip agar tidak merusak syntax JavaScript di onclick
                                $jsNamaVoter = addslashes($namaVoter);
                                $jsNamaPeserta = addslashes($namaPeserta);
                            @endphp

                            <tr class="border-b border-[#f0ede6] hover:bg-slate-50 transition"
                                data-peserta-id="{{ $v->peserta_id }}"
                                data-search="{{ strtolower($namaVoter.' '.$emailVoter) }}">
                                
                                {{-- Tanggal & Waktu --}}
                                <td class="p-3">
                                    <div class="font-mono text-[11px] text-slate-700 font-semibold">
                                        {{ \Carbon\Carbon::parse($v->tanggal_vote)->format('d M Y') }}
                                    </div>
                                    <div class="font-mono text-[10px] text-[#98958f]">
                                        {{ \Carbon\Carbon::parse($v->tanggal_vote)->format('H.i.s') }} WIB
                                    </div>
                                </td>
                                
                                {{-- Nama Voter --}}
                                <td class="p-3 font-semibold">{{ $namaVoter }}</td>
                                
                                {{-- Email Voter --}}
                                <td class="p-3 text-[#73716c]">{{ $emailVoter }}</td>
                                
                                {{-- Peserta Dipilih --}}
                                <td class="p-3">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-[#f0e6cf] text-[#8a6d1f] text-[10px] font-bold grid place-items-center">{{ $inisial }}</span>
                                        {{ $namaPeserta }}
                                    </span>
                                </td>
                                
                                {{-- Jumlah Vote --}}
                                <td class="p-3 text-right">
                                    <span class="bg-[#f7f2e4] text-[#8a6d1f] text-[11px] font-semibold px-2.5 py-1 rounded-full">{{ $v->jumlah_vote }} suara</span>
                                </td>
                                
                                {{-- Aksi (Tombol Hapus) --}}
                                <td class="p-3 text-center">
                                    <button type="button" 
                                            onclick="openDeleteVoteModal({{ $v->id }}, '{{ $jsNamaVoter }}', '{{ $jsNamaPeserta }}', {{ $v->jumlah_vote }})"
                                            class="px-3 py-1.5 bg-red-700 hover:bg-red-600 text-white text-[11px] font-medium rounded-lg border border-rose-700 transition inline-flex items-center gap-1 mx-auto">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            {{-- colspan="6" karena ada 6 kolom: Tanggal, Nama, Email, Peserta, Jumlah, Aksi --}}
                            <tr>
                                <td colspan="6" class="p-6 text-center text-[#98958f]">Belum ada suara masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </main>
</div>

<script>
    const rows = document.querySelectorAll('#voterRows tr[data-peserta-id]');
    const filterPeserta = document.getElementById('filterPeserta');
    const searchVoter = document.getElementById('searchVoter');

    function applyFilters() {
        const pesertaId = filterPeserta?.value || '';
        const q = (searchVoter?.value || '').toLowerCase();

        rows.forEach(row => {
            const matchPeserta = !pesertaId || row.dataset.pesertaId === pesertaId;
            const matchSearch = !q || row.dataset.search.includes(q);
            row.style.display = (matchPeserta && matchSearch) ? '' : 'none';
        });
    }

    filterPeserta?.addEventListener('change', applyFilters);
    searchVoter?.addEventListener('input', applyFilters);
</script>

{{-- ==========================================
    MODAL TAMBAH VOTE MANUAL
========================================== --}}
<div id="manualVoteModal" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeManualVoteModal()"></div>
    
    {{-- Modal Content --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md p-6 transform transition-all scale-100 border border-[#e3ded6]">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-slate-800">Tambah Vote Manual</h3>
            <button onclick="closeManualVoteModal()" class="text-slate-400 hover:text-rose-500 transition p-1 rounded-full hover:bg-rose-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="manualVoteForm">
            @csrf
            <div class="mb-4">
                <label class="block text-[12px] font-semibold text-slate-700 mb-1.5">Pilih Peserta <span class="text-rose-500">*</span></label>
                <select name="peserta_id" class="w-full border border-[#e3ded6] rounded-lg px-3 py-2.5 text-[12px] bg-white focus:ring-2 focus:ring-gold/30 focus:border-gold outline-none transition" required>
                    <option value="">-- Pilih Peserta --</option>
                    @foreach ($pesertaList as $p)
                        <option value="{{ $p->id }}">{{ $p->user->nama ?? 'Peserta ' . $p->id }}</option>
                    @endforeach
                </select>
            </div>
            
            {{-- ✅ INPUT NAMA VOTER (dengan name yang benar) --}}
            <div class="mb-4">
                <label class="block text-[12px] font-semibold text-slate-700 mb-1.5">
                    Nama Voter <span class="text-slate-400 font-normal">(Opsional, default: Seseorang)</span>
                </label>
                <input type="text" name="nama_voter" 
                    class="w-full border border-[#e3ded6] rounded-lg px-3 py-2.5 text-[12px] focus:ring-2 focus:ring-gold/30 focus:border-gold outline-none transition" 
                    placeholder="Contoh: Budi Santoso">
            </div>

            {{-- ✅ INPUT EMAIL VOTER (BARU) --}}
            <div class="mb-6">
                <label class="block text-[12px] font-semibold text-slate-700 mb-1.5">
                    Email Voter <span class="text-slate-400 font-normal">(Opsional)</span>
                </label>
                <input type="email" name="email_voter" 
                    class="w-full border border-[#e3ded6] rounded-lg px-3 py-2.5 text-[12px] focus:ring-2 focus:ring-gold/30 focus:border-gold outline-none transition" 
                    placeholder="Contoh: budi@email.com">
            </div>

            <div class="mb-6">
                <label class="block text-[12px] font-semibold text-slate-700 mb-1.5">Jumlah Vote <span class="text-rose-500">*</span></label>
                <input type="number" name="jumlah_vote" min="1" 
                    class="w-full border border-[#e3ded6] rounded-lg px-3 py-2.5 text-[12px] focus:ring-2 focus:ring-gold/30 focus:border-gold outline-none transition" 
                    placeholder="Masukkan jumlah vote (contoh: 10)" required>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeManualVoteModal()" 
                        class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 text-[12px] font-bold rounded-lg hover:bg-slate-200 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2.5 bg-gold text-white text-[12px] font-bold rounded-lg hover:bg-[#a06f1a] transition flex items-center justify-center gap-2">
                    <span id="btnText">Simpan Vote</span>
                    <svg id="btnLoader" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ==========================================
    JAVASCRIPT UNTUK MODAL & AJAX
========================================== --}}
<script>
    (function() {
        'use strict';

        // ==========================================
        // 1. MODAL VOTE MANUAL
        // ==========================================
        window.openManualVoteModal = function() {
            const modal = document.getElementById('manualVoteModal');
            if (modal) modal.classList.remove('hidden');
        };

        window.closeManualVoteModal = function() {
            const modal = document.getElementById('manualVoteModal');
            const form = document.getElementById('manualVoteForm');
            if (modal) modal.classList.add('hidden');
            if (form) form.reset();
        };

        const manualVoteForm = document.getElementById('manualVoteForm');
        if (manualVoteForm) {
            manualVoteForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const form = e.target;
                const btnText = document.getElementById('btnText');
                const btnLoader = document.getElementById('btnLoader');
                const submitBtn = form.querySelector('button[type="submit"]');

                if (btnText) btnText.textContent = 'Menyimpan...';
                if (btnLoader) btnLoader.classList.remove('hidden');
                if (submitBtn) submitBtn.disabled = true;

                try {
                    const response = await fetch('{{ route("admin.voters.manual-vote") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            peserta_id: form.peserta_id.value,
                            jumlah_vote: form.jumlah_vote.value,
                            nama_voter: form.nama_voter ? form.nama_voter.value.trim() : '',
                            email_voter: form.email_voter ? form.email_voter.value.trim() : ''
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        if (typeof window.showToast === 'function') window.showToast(data.message, 'success');
                        else alert(data.message);
                        window.closeManualVoteModal();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        throw new Error(data.message || 'Gagal menyimpan vote');
                    }
                } catch (error) {
                    if (typeof window.showToast === 'function') window.showToast(error.message, 'error');
                    else alert(error.message);
                } finally {
                    if (btnText) btnText.textContent = 'Simpan Vote';
                    if (btnLoader) btnLoader.classList.add('hidden');
                    if (submitBtn) submitBtn.disabled = false;
                }
            });
        }

        // ==========================================
        // 2. FILTER & SEARCH
        // ==========================================
        const voterRows = document.querySelectorAll('#voterRows tr[data-peserta-id]');
        const filterPesertaEl = document.getElementById('filterPeserta');
        const searchVoterEl = document.getElementById('searchVoter');

        function applyFilters() {
            const pesertaId = filterPesertaEl?.value || '';
            const q = (searchVoterEl?.value || '').toLowerCase();
            voterRows.forEach(row => {
                const matchPeserta = !pesertaId || row.dataset.pesertaId === pesertaId;
                const matchSearch = !q || row.dataset.search.includes(q);
                row.style.display = (matchPeserta && matchSearch) ? '' : 'none';
            });
        }

        if (filterPesertaEl) filterPesertaEl.addEventListener('change', applyFilters);
        if (searchVoterEl) searchVoterEl.addEventListener('input', applyFilters);
    })();

    // ==========================================
    // 3. MODAL HAPUS VOTE (DENGAN DEBUG LOG)
    // ==========================================
    let currentDeleteVoteId = null;

    function openDeleteVoteModal(voteId, namaVoter, namaPeserta, jumlahVote) {
        // Tampilkan modal konfirmasi (gunakan custom modal jika ada)
        if (typeof window.showWarning === 'function') {
            window.showWarning(
                `Apakah Anda yakin ingin menghapus ${jumlahVote} suara dari ${namaVoter} untuk peserta ${namaPeserta}?`,
                'Hapus Vote'
            ).then((confirmed) => {
                if (confirmed) {
                    deleteVote(voteId);
                }
            });
        } else {
            // Fallback ke confirm() browser
            if (confirm(`Hapus ${jumlahVote} suara dari ${namaVoter} untuk ${namaPeserta}?`)) {
                deleteVote(voteId);
            }
        }
    }

    window.closeDeleteVoteModal = function() {
        const modal = document.getElementById('deleteVoteModal');
        if (modal) modal.classList.add('hidden');
        currentDeleteVoteId = null;
        
        const btnText = document.getElementById('btnDeleteText');
        const btnLoader = document.getElementById('btnDeleteLoader');
        
        if (btnText) btnText.textContent = 'Ya, Hapus Vote';
        if (btnLoader) btnLoader.classList.add('hidden');
        if (btnConfirm) btnConfirm.disabled = false;
    };    

    // ==========================================
    // 4. HAPUS VOTE - MENGGUNAKAN MODAL & TOAST EXISTING
    // ==========================================
    window.openDeleteVoteModal = function(voteId, voterName, pesertaName, jumlahVote) {
    const message = `Voter: ${voterName}\nPeserta: ${pesertaName}\nJumlah: ${jumlahVote} suara\n\nApakah Anda yakin ingin menghapus vote ini? Tindakan ini tidak dapat dibatalkan.`;
    
    window.showWarning(message, 'Konfirmasi Hapus Vote')
        .then((confirmed) => {
            if (confirmed) {
                executeDeleteVote(voteId, voterName);
            }
        });
    };

    function openDeleteVoteModal(voteId, namaVoter, namaPeserta, jumlahVote) {
        // Tampilkan modal konfirmasi (gunakan custom modal jika ada)
        if (typeof window.showWarning === 'function') {
            window.showWarning(
                `Apakah Anda yakin ingin menghapus ${jumlahVote} suara dari ${namaVoter} untuk peserta ${namaPeserta}?`,
                'Hapus Vote'
            ).then((confirmed) => {
                if (confirmed) {
                    deleteVote(voteId);
                }
            });
        } else {
            // Fallback ke confirm() browser
            if (confirm(`Hapus ${jumlahVote} suara dari ${namaVoter} untuk ${namaPeserta}?`)) {
                deleteVote(voteId);
            }
        }
    }

    function deleteVote(voteId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                        document.querySelector('input[name="_token"]')?.value;
        
        // ✅ PERBAIKAN: Gunakan URL yang benar sesuai route
        const url = `/admin/voters/${voteId}`;
        
        console.log('Deleting vote:', voteId, 'URL:', url);
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Delete success:', data);
            
            // Tampilkan toast success
            if (typeof window.showToast === 'function') {
                window.showToast(data.message || 'Vote berhasil dihapus.', 'success');
            } else {
                alert('✅ ' + (data.message || 'Vote berhasil dihapus.'));
            }
            
            // Refresh halaman setelah 1 detik
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        })
        .catch(error => {
            console.error('Delete error:', error);
            
            // Tampilkan toast error
            if (typeof window.showToast === 'function') {
                window.showToast('Gagal menghapus vote: ' + error.message, 'error');
            } else {
                alert('❌ Gagal menghapus vote: ' + error.message);
            }
        });
    }

    async function executeDeleteVote(voteId, voterName) {
        try {
            const response = await fetch(`/admin/voters/${voteId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Gunakan toast jika ada, atau modal success
                if (typeof window.showToast === 'function') {
                    window.showToast(data.message, 'success');
                } else {
                    window.showSuccess(data.message);
                }
                
                setTimeout(() => location.reload(), 1000);
            } else {
                throw new Error(data.message || 'Gagal menghapus vote');
            }
        } catch (error) {
            if (typeof window.showToast === 'function') {
                window.showToast(error.message, 'error');
            } else {
                window.showError(error.message);
            }
        }
    }
</script>
</body>
</html>