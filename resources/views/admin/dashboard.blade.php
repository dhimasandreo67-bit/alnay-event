<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Dashboard Admin | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">
    <style>
        :root {
            --ink: #18202c;
            --dark: #171512;
            --dark2: #24201b;
            --gold: #ba8320;
            --bg: #f7f7f5;
            --muted: #89909a;
            --line: #e8e8e5;
            --green: #08a875;
            --orange: #e88521
        }

        * {
            box-sizing: border-box
            font-family: 'Inter', sans-serif
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font: 12px "Inter", Arial, sans-serif
        }

        .layout {
            display: flex;
            min-height: 100vh
        }

        .sidebar {
            width: 235px;
            flex: none;
            background: var(--dark);
            color: #a7a39c;
            padding: 18px 8px 14px;
            display: flex;
            flex-direction: column
        }

        .brand {
            font: 700 20px "Inter", Arial, sans-serif;
            color: #fff;
            padding: 6px 14px 30px;
            letter-spacing: -1px
        }

        .brand span {
            color: #cc962f
        }

        .admin-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--dark2);
            padding: 10px 9px;
            border-radius: 8px;
            margin-top: auto;
            color: #fff
        }

        .avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #f4f1e9;
            color: #332c22;
            font-weight: bold;
            font-size: 10px
        }

        .admin-chip small {
            display: block;
            color: #aaa59c;
            font-size: 9px;
            margin-top: 3px
        }

        .group-title {
            font-size: 9px;
            color: #807b73;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin: 0 10px 10px
        }

        .nav {
            margin-bottom: 24px
        }

        .nav a {
            display: flex;
            align-items: center;
            gap: 11px;
            color: #a9a49b;
            text-decoration: none;
            padding: 11px 10px;
            border-radius: 8px;
            margin-bottom: 3px;
            line-height: 1.3
        }

        .nav a:hover,
        .nav a.active {
            background: #302b25;
            color: #fff
        }

        .nav a.active {
            box-shadow: inset 3px 0 #c48b27
        }

        .icon {
            width: 18px;
            text-align: center;
            color: #c58d29;
            font-size: 15px
        }

        .step {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #332e28;
            color: #aba49a;
            display: grid;
            place-items: center;
            font-size: 9px
        }

        .active .step {
            background: #bf8624;
            color: #fff
        }

        .badge {
            margin-left: auto;
            border-radius: 10px;
            background: #db4d44;
            color: white;
            font-size: 9px;
            padding: 2px 5px
        }

        .live {
            margin-left: auto;
            background: #0b8f68;
            color: #d5fff1;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px
        }

        .main {
            min-width: 0;
            flex: 1
        }

        .topbar {
            height: 76px;
            background: #fff;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px
        }

        .crumb {
            color: #91959a;
            font-size: 11px
        }

        .crumb strong {
            display: block;
            color: #1e2530;
            font-size: 17px;
            margin-top: 8px
        }

        .top-actions {
            display: flex;
            gap: 9px
        }

        .btn {
            border: 1px solid #dedbd4;
            background: white;
            border-radius: 5px;
            padding: 9px 13px;
            color: #555;
            font-size: 11px
        }

        .btn.primary {
            background: var(--gold);
            border-color: var(--gold);
            color: white;
            font-weight: bold
        }

        .content {
            padding: 20px 30px 34px
        }

        .event-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 23px
        }

        .event-title {
            font: 700 19px "Inter", Arial, sans-serif;
            margin: 0 0 6px
        }

        .event-meta {
            color: #969ba0;
            font-size: 12px
        }

        .open {
            color: #0b9d75;
            background: #e8fbf4;
            border-radius: 12px;
            padding: 4px 8px;
            font-size: 11px;
            margin-left: 8px
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 15px
        }

        .card,
        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 9px;
            box-shadow: 0 2px 5px rgba(27, 29, 32, .03)
        }

        .card {
            padding: 16px 15px 12px;
            position: relative;
            overflow: hidden
        }

        .card:after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 3px;
            background: var(--gold)
        }

        .card:nth-child(2):after {
            background: var(--orange)
        }

        .card:nth-child(3):after {
            background: #ff6633
        }

        .card:nth-child(4):after {
            background: var(--green)
        }

        .card-label {
            text-transform: uppercase;
            font-size: 11px;
            color: #8d9295
        }

        .card:nth-child(2) .card-label {
            color: var(--orange)
        }

        .card:nth-child(3) .card-label {
            color: #f26036
        }

        .card:nth-child(4) .card-label {
            color: var(--green)
        }

        .number {
            font-size: 25px;
            font-weight: bold;
            margin: 12px 0 3px
        }

        .card-note {
            color: #a6a9aa;
            font-size: 11px
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 15px
        }

        .search {
            width: 275px;
            border: 1px solid var(--line);
            border-radius: 5px;
            padding: 9px 12px;
            background: white;
            font-size: 13px;
            outline: none
        }

        .filters {
            display: flex;
            gap: 7px;
            align-items: center;
            color: #8d9093;
            font-size: 12px
        }

        .filter {
            padding: 7px 9px;
            background: white;
            border: 1px solid var(--line);
            border-radius: 5px
        }

        .filter.active {
            background: #24211d;
            color: #fff
        }

        .panel {
            overflow: hidden
        }

        .panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 15px;
            border-bottom: 1px solid var(--line)
        }

        .panel-head strong {
            font-size: 15px
        }

        .table-wrap {
            overflow-x: auto
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px
        }

        th {
            background: #fcfcfb;
            text-align: left;
            color: #8d9092;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: .5px;
            padding: 12px 15px
        }

        td {
            border-top: 1px solid #f0f0ee;
            padding: 12px 15px;
            font-size: 13px;
            color: #4f555d
        }

        .person {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #252a31;
            font-weight: bold
        }

        .person small {
            display: block;
            color: #9da1a4;
            font-weight: normal;
            margin-top: 4px;
            font-size: 11px
        }

        .mini-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f4f4f2;
            border: 1px solid #e7e7e4;
            display: grid;
            place-items: center;
            color: #8b8e90;
            font-size: 10px
        }

        .status {
            border: 1px solid;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            white-space: nowrap
        }

        .status.wait {
            color: #e69b25;
            background: #fffaf0;
            border-color: #f5d895
        }

        .status.ok {
            color: #09a376;
            background: #eefff9;
            border-color: #a7ead5
        }

        .status.no {
            color: #e75c62;
            background: #fff3f2;
            border-color: #f4c2bd
        }

        .detail {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 7px 10px;
            font-size: 11px
        }

        .footer-note {
            color: #96999c;
            font-size: 12px;
            padding: 14px 15px
        }

        @media(max-width:900px) {
            .sidebar {
                width: 70px
            }

            .brand {
                font-size: 0;
                padding: 15px 14px 30px
            }

            .brand:before {
                content: 'A';
                font: 700 23px "Inter", Arial, sans-serif;
                color: #c58d29
            }

            .group-title,
            .nav a span:not(.icon),
            .admin-chip div {
                display: none
            }

            .nav a {
                justify-content: center
            }

            .admin-chip {
                justify-content: center
            }

            .topbar,
            .content {
                padding-left: 18px;
                padding-right: 18px
            }

            .stats {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        @media(max-width:600px) {
            .topbar {
                height: auto;
                padding-top: 15px;
                padding-bottom: 15px;
                gap: 10px;
                align-items: flex-start
            }

            .top-actions {
                flex-direction: column
            }

            .event-header {
                display: block
            }

            .event-header .btn {
                margin-top: 12px
            }

            .toolbar {
                display: block
            }

            .search {
                width: 100%;
                margin-bottom: 10px
            }

            .filters {
                overflow: auto
            }

            .stats {
                gap: 8px
            }

            .number {
                font-size: 21px
            }
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    </style>
</head>

<body>
    <div class="layout pt-[76px]">
        @include('admin.partials.sidebar', [
            'active' => 'dashboard',
            'event' => $event,
            'menungguReview' => $menungguReview,
        ])
        @include('admin.partials.header', ['variant' => 'dashboard', 'event' => $event])
        {{-- <div class="hidden"></div> --}}
        <main class="main">
            <section class="content">
                <div class="event-header">
                    <div><span class="open" class="pt-4">Pendaftaran Dibuka</span><span class="event-meta"> &nbsp;01 Sep 2026,
                            00:00 – 20 Sep 2026, 23:59 WIB</span>
                        <h1 class="event-title">{{ $event?->nama ?? 'PEMBUKAAN PENDAFTARAN ALNAY OF THE YEAR 2026' }}
                        </h1><span class="event-meta">Pendaftaran: 01 – 20 Sep 2026 &nbsp; · &nbsp; Kuota Maks: 12
                            orang</span>
                    </div><button class="btn primary" id="new-event">＋ Tambah Peserta Manual</button>
                </div>
                <div class="stats">
                    <div class="card"><span class="card-label">Total Pendaftar</span>
                        <div class="number">{{ $totalPendaftar }}</div><span class="card-note">Peserta tercatat</span>
                    </div>
                    <div class="card"><span class="card-label">Menunggu Review</span>
                        <div class="number">{{ $menungguReview }}</div><span class="card-note">Prioritas Kurasi</span>
                    </div>
                    <div class="card"><span class="card-label">Perlu Revisi Data</span>
                        <div class="number">{{ $perluRevisi }}</div><span class="card-note">Dokumen kurang</span>
                    </div>
                    <div class="card"><span class="card-label">Diterima</span>
                        <div class="number">{{ $diterima }}</div><span class="card-note">Lolos Final Lineup</span>
                    </div>
                </div>
                <div class="toolbar"><input class="search" id="search"
                        placeholder="⌕  Cari nama atau nomor HP peserta...">
                    <div class="filters"><button class="filter active">Semua {{ $totalPendaftar }}</button><button
                            class="filter">Menunggu {{ $menungguReview }}</button><button class="filter">Diterima
                            {{ $diterima }}</button><button class="filter">Ditolak {{ $perluRevisi }}</button>
                    </div>
                </div>
                <div class="panel" id="verification">
                    <div class="panel-head"><strong>Daftar Peserta</strong><span class="event-meta">Update terakhir:
                            baru saja</span></div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Peserta</th>
                                    <th>Umur</th>
                                    <th>Tinggi / Berat</th>
                                    <th>Kontak</th>
                                    <th>Status</th>
                                    <th>Aksi Cepat</th>
                                </tr>
                            </thead>
                            <tbody id="participantRows">
                                @forelse($peserta as $item)
                                    <tr>
                                        <td>
                                            <div class="person"><span
                                                    class="mini-avatar">{{ strtoupper(substr($item->user?->nama ?? 'NA', 0, 2)) }}</span>
                                                <div>{{ $item->user?->nama ?? 'Peserta Baru' }}<small>#{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                                        · {{ $item->event?->nama ?? 'Event' }}</small></div>
                                            </div>
                                        </td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td><span
                                                class="status {{ $item->status === 'diterima' ? 'ok' : ($item->status === 'ditolak' ? 'no' : 'wait') }}">{{ str_replace('_', ' ', ucfirst($item->status)) }}</span>
                                        </td>
                                        <td><button class="detail">Detail</button></td>
                                </tr>@empty<tr>
                                        <td colspan="6" style="text-align:center;padding:34px;color:#999">Belum ada
                                            peserta tercatat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="footer-note">Menampilkan {{ $peserta->count() }} peserta terdaftar</div>
                </div>
            </section>
        </main>
    </div>
    <script>
        document.getElementById('search').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#participantRows tr').forEach(r => r.style.display = r.innerText
                .toLowerCase().includes(q) ? '' : 'none')
        });
        const adminLinks = {
            '#form': '{{ route('admin.form-builder', $event?->id) }}',
            '#verification': '{{ route('admin.registrations', $event?->id) }}',
            '#activation': '{{ route('admin.activation', $event?->id) }}',
            '#settings': '{{ route('admin.voting.settings', $event?->id) }}',
            '#voters': '{{ route('admin.voters', $event?->id) }}',
            '#leaderboard': '{{ route('admin.leaderboard', $event?->id) }}',
            '#new-event': '{{ route('admin.registrations', $event?->id) }}'
        };
        document.querySelectorAll('a[href^="#"]').forEach(a => a.addEventListener('click', function(e) {
            if (adminLinks[this.getAttribute('href')]) {
                e.preventDefault();
                location.href = adminLinks[this.getAttribute('href')]
            }
        }))
    </script>
</body>

</html>