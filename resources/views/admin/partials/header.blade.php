@vite(['resources/css/app.css', 'resources/js/app.js'])
<header
    class="fixed top-0 right-0 left-[235px] z-[15] flex h-[76px] min-h-[76px] items-center justify-between border-b border-[#e8e8e5] bg-white px-[30px] font-sans text-[#1e2530] max-[900px]:left-[70px] max-[900px]:px-[18px]">
    @if (($variant ?? 'module') === 'dashboard')
        <div class="text-[11px] text-[#91959a]"><strong
                class="mt-2 block text-[17px] text-[#1e2530]">{{ $event?->nama ?? 'Verifikasi Pendaftaran Peserta' }}</strong>
        </div>
        <div class="flex items-center gap-[9px]">
            <form method="POST" action="{{ route('admin.logout.web') }}">@csrf<button
                    class="inline-flex cursor-pointer items-center justify-center rounded-[5px] border border-[#dedbd4] bg-white px-[13px] py-[9px] text-[11px] font-medium text-[#56524c]"
                    type="submit">Keluar</button></form>
        </div>
    @elseif(($variant ?? 'module') === 'builder')
        <div class="flex min-w-0 flex-col gap-1 text-[11px] text-[#91959a]"><strong class="truncate text-[17px] text-[#1e2530]">Formulir Pendaftaran -
                {{ $event?->nama ?? 'Event Baru' }}</strong></div>
                
        {{-- BUTTON CONTAINER TOGGLE STATUS (Seluruh area ini dapat diklik) --}}
        <label for="toggleFormStatus" 
            class="flex items-center gap-3 bg-white border border-[#e5e1db] hover:border-[#c38b22] rounded-xl px-4 py-2 shadow-sm cursor-pointer select-none transition-all duration-200">
            
            {{-- Native Hidden Checkbox --}}
            <input type="checkbox" id="toggleFormStatus" class="sr-only peer" 
                {{ (isset($event) && (bool)$event->is_active) ? 'checked' : '' }}
                data-event-id="{{ $event?->id }}">

            {{-- Label Teks Status --}}
            <div class="flex flex-col min-w-0 pointer-events-none">
                <span class="text-xs font-semibold tracking-wider text-[#91959a]">
                    Status Pendaftaran
                </span>
                <strong id="statusText" class="text-[11px] font-bold truncate transition-colors duration-200 
                    {{ (isset($event) && (bool)$event->is_active) ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ (isset($event) && (bool)$event->is_active) ? 'BUKA' : 'DITUTUP' }}
                </strong>
            </div>
        </label>

        <div class="flex shrink-0 items-center gap-[9px]">
            <a class="inline-flex items-center justify-center whitespace-nowrap rounded-[5px] border border-[#dedbd4] bg-white px-[13px] py-[9px] text-[11px] font-medium text-[#56524c] no-underline"
                href="{{ $event ? route('public.registration', $event) : '#' }}" target="_blank">Lihat Form Publik</a>
            <button class="inline-flex cursor-pointer items-center justify-center whitespace-nowrap rounded-[5px] border border-[#ba8320] bg-[#ba8320] px-[13px] py-[9px] text-[11px] font-bold text-white"
                type="button" id="saveFormHeader">Simpan Formulir</button>
        </div>
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

    // Script Toggle Status dengan Hit Klik Seluruh Area Button
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('toggleFormStatus');
        const statusText = document.getElementById('statusText');

        if (toggle) {
            toggle.addEventListener('change', async function () {
                const isActive = this.checked;
                const eventId = this.dataset.eventId;

                // 1. Update Teks Status secara langsung
                if (statusText) {
                    statusText.textContent = isActive ? 'BUKA' : 'DITUTUP';
                    statusText.className = `text-[11px] font-bold truncate transition-colors duration-200 ${
                        isActive ? 'text-emerald-600' : 'text-rose-600'
                    }`;
                }

                // 2. Kirim update ke Backend via Request AJAX
                if (eventId) {
                    try {
                        const response = await fetch(`/admin/events/${eventId}/toggle-status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ is_active: isActive })
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            if (window.showAdminToast) {
                                window.showAdminToast(data.message || 'Status berhasil diperbarui!');
                            }
                        } else {
                            // Revert jika gagal di server
                            this.checked = !isActive;
                            if (statusText) {
                                statusText.textContent = !isActive ? 'BUKA' : 'DITUTUP';
                                statusText.className = `text-[11px] font-bold truncate transition-colors duration-200 ${
                                    !isActive ? 'text-emerald-600' : 'text-rose-600'
                                }`;
                            }
                            alert(data.message || 'Gagal mengubah status di database.');
                        }
                    } catch (error) {
                        console.error('Error updating status:', error);
                        // Revert jika koneksi gagal
                        this.checked = !isActive;
                        if (statusText) {
                            statusText.textContent = !isActive ? 'BUKA' : 'DITUTUP';
                            statusText.className = `text-[11px] font-bold truncate transition-colors duration-200 ${
                                !isActive ? 'text-emerald-600' : 'text-rose-600'
                            }`;
                        }
                        alert('Terjadi kesalahan jaringan.');
                    }
                }
            });
        }
    });
</script>