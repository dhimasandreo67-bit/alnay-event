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

            @if ($event)
    <div class="flex items-center justify-between mb-5 gap-3 flex-wrap">
        <p class="text-[#73716c] text-[12px]">
            {{ $event->nama ?? 'Belum ada event' }}
        </p>
        <button type="button" onclick="openManualVoteModal()"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#C28E27] hover:bg-[#a3721f] text-white text-[12px] font-bold rounded-lg transition shadow-sm cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            + Vote Manual
        </button>
    </div>
@endif

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

    function openManualVoteModal() {
    const modal = document.getElementById('modalManualVote');
    const form = document.getElementById('formManualVote');
    const errorEl = document.getElementById('manualVoteError');

    if (form) form.reset();
    if (errorEl) errorEl.classList.add('hidden');

    const eventInput = form?.querySelector('input[name="event_id"]');
    if (eventInput) {
        eventInput.value = @json($event->id ?? null);
    }

    modal?.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeManualVoteModal() {
    document.getElementById('modalManualVote')?.classList.add('hidden');
    document.body.style.overflow = '';
}

document.getElementById('modalManualVote')?.addEventListener('click', function (e) {
    if (e.target === this) closeManualVoteModal();
});

async function submitManualVote(e) {
    e.preventDefault();

    const form = e.target;
    const btn = document.getElementById('btnSubmitManualVote');
    const errorEl = document.getElementById('manualVoteError');
    const original = btn.textContent;

    btn.disabled = true;
    btn.textContent = 'Menyimpan...';
    errorEl.classList.add('hidden');

    try {
        const res = await fetch(@json(route('admin.voters.manual-vote')), {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: new FormData(form),
        });

        const data = await res.json();

        if (!res.ok || !data.success) {
            throw new Error(data.message || 'Gagal menambah vote.');
        }

        if (typeof window.showToast === 'function') {
            window.showToast(data.message, 'success');
        } else {
            alert(data.message);
        }

        closeManualVoteModal();
        setTimeout(() => location.reload(), 800);
    } catch (err) {
        errorEl.textContent = err.message;
        errorEl.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.textContent = original;
    }
}
</script>

{{-- MODAL VOTE MANUAL --}}
<div id="modalManualVote" class="hidden fixed inset-0 z-[999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-[#e3ded6]">
        <div class="flex items-center justify-between pb-4 border-b border-[#e3ded6] mb-4">
            <div>
                <h3 class="text-base font-bold text-[#22201d]">Vote Manual</h3>
                <p class="text-[11px] text-[#73716c] mt-0.5">Tambah suara langsung (status lunas)</p>
            </div>
            <button type="button" onclick="closeManualVoteModal()"
                class="text-gray-400 hover:text-gray-600 p-1 rounded-lg cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="formManualVote" onsubmit="submitManualVote(event)">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id ?? '' }}">

            <div class="space-y-3">
                <div>
                    <label class="block text-[11px] font-bold text-[#73716c] mb-1.5">
                        Peserta <span class="text-red-500">*</span>
                    </label>
                    <select name="peserta_id" required
                        class="w-full border border-[#e3ded6] rounded-lg px-3 py-2.5 text-[12px] bg-white focus:outline-none focus:border-[#C28E27]">
                        <option value="">— Pilih peserta —</option>
                        @foreach ($pesertaList ?? [] as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->user->nama ?? (method_exists($p, 'namaTampil') ? $p->namaTampil() : 'Peserta #'.$p->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-[#73716c] mb-1.5">
                        Jumlah Suara <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah_vote" min="1" max="10000" value="1" required
                        class="w-full border border-[#e3ded6] rounded-lg px-3 py-2.5 text-[12px] focus:outline-none focus:border-[#C28E27]">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-[#73716c] mb-1.5">
                        Nama Voter <span class="text-[#98958f] font-normal">(opsional)</span>
                    </label>
                    <input type="text" name="nama_voter" maxlength="255" placeholder="Admin Manual"
                        class="w-full border border-[#e3ded6] rounded-lg px-3 py-2.5 text-[12px] focus:outline-none focus:border-[#C28E27]">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-[#73716c] mb-1.5">
                        Email Voter <span class="text-[#98958f] font-normal">(opsional)</span>
                    </label>
                    <input type="email" name="email_voter" maxlength="255" placeholder="admin@manual.local"
                        class="w-full border border-[#e3ded6] rounded-lg px-3 py-2.5 text-[12px] focus:outline-none focus:border-[#C28E27]">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-[#73716c] mb-1.5">
                        Catatan <span class="text-[#98958f] font-normal">(opsional)</span>
                    </label>
                    <textarea name="catatan" rows="2" maxlength="500" placeholder="Alasan / keterangan"
                        class="w-full border border-[#e3ded6] rounded-lg px-3 py-2.5 text-[12px] focus:outline-none focus:border-[#C28E27]"></textarea>
                </div>
            </div>

            <div id="manualVoteError" class="hidden mt-3 rounded-lg bg-rose-50 border border-rose-200 px-3 py-2 text-xs text-rose-700"></div>

            <div class="flex gap-2 mt-5">
                <button type="button" onclick="closeManualVoteModal()"
                    class="flex-1 px-4 py-2.5 border border-[#e3ded6] bg-white rounded-lg text-[#22201d] font-bold text-[12px] hover:bg-gray-50 transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" id="btnSubmitManualVote"
                    class="flex-1 px-4 py-2.5 bg-[#C28E27] hover:bg-[#a3721f] text-white font-bold text-[12px] rounded-lg transition cursor-pointer">
                    Tambah Suara
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>