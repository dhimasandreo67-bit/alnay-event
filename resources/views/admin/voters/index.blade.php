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

{{-- ==========================================
    JAVASCRIPT UNTUK MODAL & AJAX
========================================== --}}
<script>
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
</body>
</html>