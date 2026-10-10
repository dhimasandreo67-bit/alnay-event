<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Event | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/admin-sidebar.css')); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
</head>

<body class="bg-cream text-ink font-sans text-[13px]">
    <div class="flex min-h-screen w-full">
        <main class="flex-1 min-w-0 ml-[235px]">
            
            <header class="flex items-center justify-between px-6 py-4 border-b border-[#e5e1db] bg-white">
                <div>
                    <h1 class="font-bold text-[16px] m-0">Kelola Data Event</h1>
                    <p class="text-[10px] text-[#8a8984] m-0">ALNAY.ID — Daftar seluruh event terdaftar</p>
                </div>
                <a href="<?php echo e(route('admin.events.create')); ?>"
                    class="inline-flex items-center gap-2 rounded-lg
                            bg-gradient-to-r from-yellow-500 to-amber-600
                            border border-yellow-400
                            px-5 py-2.5
                            text-sm font-semibold text-white
                            shadow-md shadow-amber-500/20
                            transition-all duration-200
                            hover:-translate-y-0.5
                            hover:from-yellow-600 hover:to-amber-700
                            hover:shadow-lg hover:shadow-amber-500/30
                            focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2
                            active:translate-y-0">
                        <span class="text-base leading-none">+</span>
                        <span>Event Baru</span>
                    </a>
            </header>

            <div class="p-6">

                
                <div class="mb-6 bg-white rounded-xl border border-slate-200 p-4 overflow-x-auto">
                    <div class="flex items-center justify-between gap-4 flex-wrap overflow-x-auto">
                        <div class="flex items-center gap-3 flex-1 min-w-0 overflow-x-auto">
                            <label for="select-event" class="text-sm font-semibold text-slate-700 whitespace-nowrap">
                                Event Aktif:
                            </label>
                            <select id="select-event"
                                onchange="window.location.href='<?php echo e(route('admin.events.index')); ?>?event=' + this.value"
                                class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                                <?php $__currentLoopData = $eventList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($e->id); ?>"
                                        <?php echo e(($activeEvent?->id ?? 0) === $e->id ? 'selected' : ''); ?>>
                                        <?php echo e($e->nama); ?> (ID: <?php echo e($e->id); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <?php if($activeEvent): ?>
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('admin.events.edit', $activeEvent->id)); ?>"
                                    class="px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm font-medium rounded-lg border border-amber-200 transition">
                                    Edit Event Ini
                                </a>
                                <a href="<?php echo e(route('admin.activation', $activeEvent->id)); ?>"
                                    class="px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-medium rounded-lg border border-emerald-200 transition">
                                    Aktivasi Peserta
                                </a>
                                <a href="<?php echo e(route('admin.voting.settings', $activeEvent->id)); ?>"
                                    class="px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-medium rounded-lg border border-blue-200 transition">
                                    Pengaturan Voting
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <?php if($activeEvent): ?>
                        <div class="mt-3 pt-3 border-t border-slate-100 grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                            <div>
                                <span class="text-slate-500">Status:</span>
                                <?php
                                    $statusClass = match ($activeEvent->status) {
                                        'akan_datang' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'berlangsung' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'selesai' => 'bg-orange-100 text-orange-700 border-orange-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                ?>
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border ml-1 <?php echo e($statusClass); ?>">
                                    <?php echo e($activeEvent->status_label); ?>

                                </span>
                            </div>
                            <div>
                                <span class="text-slate-500">Peserta:</span>
                                <span class="font-semibold text-slate-800 ml-1">
                                    <?php echo e($activeEvent->pesertas()->count()); ?> / <?php echo e($activeEvent->kuota_peserta ?? '-'); ?>

                                </span>
                            </div>
                            <div>
                                <span class="text-slate-500">Biaya Daftar:</span>
                                <span class="font-semibold text-slate-800 ml-1">
                                    Rp <?php echo e(number_format($activeEvent->biaya_pendaftaran ?? 0, 0, ',', '.')); ?>

                                </span>
                            </div>
                            <div>
                                <span class="text-slate-500">Lokasi:</span>
                                <span class="font-semibold text-slate-800 ml-1 truncate block">
                                    <?php echo e($activeEvent->lokasi ?? '-'); ?>

                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if(session('success')): ?>
                    <div
                        class="mb-4 rounded-lg bg-emerald-50 text-emerald-700 px-4 py-3 text-[12px] border border-emerald-200">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                
                <div class="bg-white border border-[#e3ded6] rounded-xl overflow-hidden shadow-sm overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-[#fcfbf9] border-b border-[#e3ded6] text-[11px] text-[#8a8984] uppercase tracking-wider">
                                <th class="py-3.5 px-4 font-bold">Nama Event & Lokasi</th>
                                <th class="py-3.5 px-4 font-bold">Status</th>
                                <th class="py-3.5 px-4 font-bold">Pelaksanaan</th>
                                <th class="py-3.5 px-4 font-bold">Tarif</th>
                                <th class="py-3.5 px-4 font-bold">Kuota</th>
                                <th class="py-3.5 px-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e3ded6]">
                            <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr
                                    class="hover:bg-slate-50 transition <?php echo e(($activeEvent?->id ?? 0) === $item->id ? 'bg-amber-50/50 border-l-4 border-l-amber-400' : ''); ?>">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <img src="<?php echo e($item->banner ? asset('storage/' . $item->banner) : 'https://via.placeholder.com/150'); ?>"
                                                class="w-14 h-10 object-cover rounded-md border border-[#e5e1db]">
                                            <div>
                                                <div class="font-bold text-ink text-[13px] line-clamp-1">
                                                    <?php echo e($item->nama); ?>

                                                </div>
                                                <span class="text-[10px] text-[#8a8984] block mt-0.5">
                                                    Lokasi: <?php echo e($item->lokasi); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    
                                    <td class="py-3 px-4 text-[11px]">
                                        <?php if($item->status === 'akan_datang'): ?>
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>
                                                Akan Datang
                                            </span>
                                        <?php elseif($item->status === 'sedang_berlangsung'): ?>
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                                Sedang Berlangsung
                                            </span>
                                        <?php elseif($item->status === 'telah_berakhir'): ?>
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-200 whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 shrink-0"></span>
                                                Telah Berakhir
                                            </span>
                                        <?php else: ?>
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-50 text-gray-600 border border-gray-200 whitespace-nowrap">
                                                <?php echo e($item->status_label ?? 'Tidak Diketahui'); ?>

                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="py-3 px-4 text-[11px]">
                                        <div class="font-semibold text-ink">
                                            <?php echo e(\Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y')); ?>

                                        </div>
                                        <div class="text-[#8a8984]">
                                            <?php echo e($item->waktu_mulai); ?> WIB
                                        </div>
                                    </td>

                                    <td class="py-3 px-4 text-[11px]">
                                        <div>Daftar: <strong
                                                class="text-ink"><?php echo e($item->biaya_pendaftaran > 0 ? 'Rp ' . number_format($item->biaya_pendaftaran, 0, ',', '.') : 'Gratis'); ?></strong>
                                        </div>
                                        <div class="text-[#8a8984]">Vote:
                                            <?php echo e($item->harga_per_vote > 0 ? 'Rp ' . number_format($item->harga_per_vote, 0, ',', '.') : '—'); ?>

                                        </div>
                                    </td>

                                    <td class="py-3 px-4 text-[11px]">
                                        <span
                                            class="inline-block bg-[#f7f5f0] border border-[#e5e1db] rounded px-2 py-1 font-semibold text-ink">
                                            <?php echo e($item->kuota_peserta); ?> Peserta
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="<?php echo e(route('admin.events.edit', $item->id)); ?>"
                                                class="px-2.5 py-1.5 rounded-md border border-[#e5e1db] bg-white text-ink text-[11px] font-bold hover:bg-[#f7f5f0]">
                                                Edit
                                            </a>

                                            <form action="<?php echo e(route('admin.events.destroy', $item->id)); ?>"
                                                method="POST"
                                                onsubmit="event.preventDefault(); konfirmasi('Yakin ingin menghapus event ini?', () => this.submit(), { title: 'Hapus Event', confirmText: 'Ya, Hapus', confirmColor: '#dc2626' });"
                                                class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit"
                                                    onclick="event.preventDefault(); konfirmasi('Yakin ingin menghapus event ini?', () => this.form.submit(), { title: 'Hapus Event', confirmText: 'Ya, Hapus', confirmColor: '#dc2626' });"
                                                    class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-lg border border-red-700 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-[#8a8984]">
                                        Belum ada data event. <a href="<?php echo e(route('admin.events.create')); ?>"
                                            class="text-gold underline font-bold">Buat Event Pertama</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <?php echo $__env->make('admin.partials.sidebar', ['active' => 'events-index'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('partials.swal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html>
<?php /**PATH D:\alnayevent\resources\views/admin/events/index.blade.php ENDPATH**/ ?>