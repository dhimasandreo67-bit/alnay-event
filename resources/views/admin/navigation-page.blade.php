@include('admin.partials.sidebar', ['active' => $page, 'event' => $event])
@include('admin.partials.header', ['variant' => 'module', 'event' => $event])
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    @include('admin.partials.pwa-head')
    <title>{{ $pageTitle }} | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark: #171512;
            --dark2: #2d2924;
            --gold: #c38a21;
            --bg: #f7f6f3;
            --line: #e7e2da;
            --muted: #8c8982;
            --ink: #24211d
        }

        * {
            box-sizing: border-box
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
            background: var(--dark);
            color: #aaa59d;
            padding: 18px 8px;
            display: flex;
            flex-direction: column;
            flex: none
        }

        .brand {
            color: #fff;
            font: 700 20px "Inter", Arial, sans-serif;
            padding: 5px 14px 31px
        }

        .brand span {
            color: #ca922b
        }

        .section {
            color: #777169;
            font-size: 9px;
            letter-spacing: .6px;
            text-transform: uppercase;
            margin: 0 10px 9px
        }

        .nav {
            margin-bottom: 22px
        }

        .nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #a8a39b;
            text-decoration: none;
            padding: 10px;
            border-radius: 8px;
            line-height: 1.3
        }

        .nav a:hover,
        .nav a.active {
            background: var(--dark2);
            color: #fff
        }

        .step {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #332f2a;
            font-size: 9px;
            flex: none
        }

        .active .step {
            background: var(--gold);
            color: #fff
        }

        .badge,
        .live {
            margin-left: auto;
            border-radius: 10px;
            padding: 3px 6px;
            font-size: 8px
        }

        .badge {
            background: #df5148;
            color: #fff
        }

        .live {
            background: #078e68;
            color: #dffff5
        }

        .admin {
            margin-top: auto;
            background: #28241f;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            gap: 9px;
            color: #fff
        }

        .avatar {
            width: 28px;
            height: 28px;
            background: #f5f0e6;
            color: #4b4334;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 9px;
            font-weight: bold
        }

        .admin small {
            display: block;
            color: #aaa39b;
            font-size: 9px;
            margin-top: 3px
        }

        .main {
            flex: 1;
            min-width: 0
        }

        .top {
            height: 75px;
            background: #fff;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px
        }

        .crumb {
            color: #92908b;
            font-size: 10px
        }

        .crumb strong {
            display: block;
            color: var(--ink);
            font-size: 17px;
            margin-top: 7px
        }

        .top a {
            border: 1px solid #ddd7cf;
            border-radius: 5px;
            padding: 9px 13px;
            color: #56524c;
            text-decoration: none;
            font-size: 10px
        }

        .content {
            padding: 28px 30px
        }

        .hero {
            max-width: 800px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 26px 28px;
            box-shadow: 0 3px 10px #00000008
        }

        .eyebrow {
            color: var(--gold);
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .8px;
            font-weight: bold
        }

        .hero h1 {
            font: 700 29px "Inter", Arial, sans-serif;
            margin: 12px 0 8px
        }

        .hero p {
            color: var(--muted);
            line-height: 1.6;
            margin: 0;
            max-width: 620px
        }

        .eventbar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 24px;
            padding: 12px;
            background: #faf9f6;
            border: 1px solid var(--line);
            border-radius: 6px
        }

        .eventbar label {
            color: #77736d;
            font-size: 10px
        }

        .eventbar select {
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 4px;
            padding: 7px;
            font-size: 10px;
            min-width: 220px
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 18px;
            max-width: 800px
        }

        .card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 17px
        }

        .card b {
            font-size: 13px
        }

        .card p {
            color: var(--muted);
            font-size: 10px;
            line-height: 1.5;
            margin-bottom: 0
        }

        .empty {
            margin-top: 18px;
            border: 1px dashed #d3c9bb;
            border-radius: 8px;
            padding: 24px;
            color: #918d84;
            background: #fff;
            max-width: 800px
        }

        @media(max-width:700px) {
            .sidebar {
                width: 62px
            }

            .brand {
                font-size: 0;
                padding: 7px 15px 30px
            }

            .brand:before {
                content: 'A';
                font: 700 23px "Inter", Arial, sans-serif;
                color: #c8922b
            }

            .section,
            .nav a span:not(.step),
            .badge,
            .live,
            .admin div {
                display: none
            }

            .nav a {
                justify-content: center
            }

            .admin {
                justify-content: center
            }

            .top {
                padding: 13px 16px;
                height: auto
            }

            .top a {
                white-space: nowrap
            }

            .content {
                padding: 18px 16px
            }

            .hero h1 {
                font-size: 24px
            }

            .cards {
                grid-template-columns: 1fr
            }

            .eventbar {
                align-items: flex-start;
                flex-direction: column
            }
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', Arial, sans-serif
        }
    </style>
</head>

<body>
    <div class="layout pt-[76px]">
        <aside class="sidebar">
            <div class="brand">ALNAY<span>.ID</span></div>
            <div class="nav">
                <p class="section">Event & Peserta</p><a href="{{ route('admin.dashboard') }}"><span
                        class="step">1</span><span>Buat Event Baru</span></a><a
                    href="{{ route('admin.form-builder', $event?->id) }}"><span class="step">2</span><span>Buat Form
                        Pendaftaran Event Baru</span></a><a class="{{ $page === 'registrations' ? 'active' : '' }}"
                    href="{{ route('admin.registrations', $event?->id) }}"><span class="step">3</span><span>Verifikasi
                        Pendaftaran Peserta</span><b class="badge">6</b></a><a
                    class="{{ $page === 'activation' ? 'active' : '' }}"
                    href="{{ route('admin.activation', $event?->id) }}"><span class="step">4</span><span>Aktivasi Akun
                        Peserta</span></a>
            </div>
            <div class="nav">
                <p class="section">Voting</p><a class="{{ $page === 'voting-settings' ? 'active' : '' }}"
                    href="{{ route('admin.voting.settings', $event?->id) }}"><span
                        class="step">4</span><span>Pengaturan Event & Voting</span></a><a
                    class="{{ $page === 'voters' ? 'active' : '' }}"
                    href="{{ route('admin.voters', $event?->id) }}"><span class="step">5</span><span>Daftar
                        Voter</span><b class="live">Live</b></a><a
                    class="{{ $page === 'leaderboard' ? 'active' : '' }}"
                    href="{{ route('admin.leaderboard', $event?->id) }}"><span class="step">6</span><span>Leaderboard
                        Suara Voting</span></a>
            </div>
            <div class="admin"><span class="avatar">SA</span>
                <div><strong>{{ session('admin_name', 'MAS RADEN') }}</strong><small>Admin Event</small></div>
            </div>
        </aside>
        <main class="main">
            <section class="content">
                <div class="hero"><span class="eyebrow">Ruang kerja admin</span>
                    <h1>{{ $pageTitle }}</h1>
                    <p>{{ $pageDescription }} Semua perubahan pada event aktif akan tersimpan dan terhubung ke data
                        peserta serta transaksi voting.</p>
                    <div class="eventbar"><label>Event aktif</label><select
                            onchange="if(this.value) location.href=this.value">
                            @foreach ($events as $item)
                                <option
                                    value="{{ route('admin.' . ($page === 'event-create' ? 'events.create' : $page), $item->id) }}"
                                    @selected($event?->id === $item->id)>{{ $item->nama }}</option>
                            @endforeach
                        </select></div>
                </div>
                @if ($page === 'registrations' && $event)
                    <form id="manualParticipantForm" class="hero" method="POST" enctype="multipart/form-data"
                        action="{{ route('admin.registrations.manual.store', $event) }}">
                        @csrf
                        <span class="eyebrow">Input manual</span>
                        <h2>Data Peserta</h2>
                        <p>Gunakan pertanyaan yang sudah disimpan di Form Builder untuk mencatat peserta secara manual.</p>
                        <div class="manual-fields" style="display:grid;gap:14px;margin:20px 0">
                            <div class="control" style="display:grid;gap:6px"><label for="manualNama">Nama peserta</label><input id="manualNama" name="nama" required style="border:1px solid var(--line);border-radius:5px;padding:10px"></div>
                            <div class="control" style="display:grid;gap:6px"><label for="manualEmail">Email peserta</label><input id="manualEmail" name="email" type="email" required style="border:1px solid var(--line);border-radius:5px;padding:10px"></div>
                            @forelse ($questions as $question)
                                @php($config = $question->konfigurasi ?? [])
                                @if ($question->tipe_input === 'heading')
                                    <h3>{{ $question->label }}</h3>
                                @elseif ($question->tipe_input === 'subheading')
                                    <p>{{ $question->label }}</p>
                                @else
                                    <div class="control" style="display:grid;gap:6px"><label for="answer-{{ $question->id }}">{{ $question->label }} @if ($question->wajib)<strong>*</strong>@endif</label>
                                        @if ($question->tipe_input === 'checkbox')
                                            <div style="display:grid;gap:8px">@foreach ($question->pilihan_jawaban ?? [] as $option)<label style="font-weight:normal"><input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option }}"> {{ $option }}</label>@endforeach</div>
                                        @elseif (in_array($question->tipe_input, ['select', 'radio'], true))
                                            <select id="answer-{{ $question->id }}" name="answers[{{ $question->id }}]" style="border:1px solid var(--line);border-radius:5px;padding:10px" @required($question->wajib)>
                                                <option value="">Pilih jawaban</option>
                                                @foreach ($question->pilihan_jawaban ?? [] as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach
                                            </select>
                                        @elseif ($question->tipe_input === 'textarea')
                                            <textarea id="answer-{{ $question->id }}" name="answers[{{ $question->id }}]" placeholder="{{ $config['placeholder'] ?? '' }}" style="border:1px solid var(--line);border-radius:5px;padding:10px;min-height:80px" @required($question->wajib)></textarea>
                                        @elseif ($question->tipe_input === 'file')
                                            <input id="answer-{{ $question->id }}" name="answers[{{ $question->id }}]" type="file" @required($question->wajib)>
                                        @else
                                            <input id="answer-{{ $question->id }}" name="answers[{{ $question->id }}]" type="{{ in_array($question->tipe_input, ['email', 'number'], true) ? $question->tipe_input : 'text' }}" @if ($question->tipe_input === 'number') inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" onkeydown="if (['e', 'E', '+', '-', '.'].includes(event.key)) event.preventDefault()" @endif placeholder="{{ $config['placeholder'] ?? '' }}" style="border:1px solid var(--line);border-radius:5px;padding:10px" @required($question->wajib)>
                                        @endif
                                        @if (!empty($config['help']))<small>{{ $config['help'] }}</small>@endif
                                    </div>
                                @endif
                            @empty
                                <p>Belum ada pertanyaan. Buat dan simpan formulir terlebih dahulu.</p>
                            @endforelse
                        </div>
                        @if ($questions->contains(fn ($question) => ! in_array($question->tipe_input, ['heading', 'subheading'], true)))
                            <button class="btn primary" type="submit">Simpan Peserta</button>
                        @endif
                    </form>
                @endif
                <div class="cards">
                    <div class="card"><b>Event aktif</b>
                        <p>{{ $event?->nama ?? 'Belum ada event' }}</p>
                    </div>
                    <div class="card"><b>Status workspace</b>
                        <p>Siap digunakan untuk pengelolaan data.</p>
                    </div>
                    <div class="card"><b>Integrasi database</b>
                        <p>Terhubung ke data peserta, form, dan voting.</p>
                    </div>
                </div>
                <div class="empty">Modul <strong>{{ $pageTitle }}</strong> siap dikembangkan dari halaman ini.
                </div>
            </section>
        </main>
    </div>
    @if ($page === 'registrations')
        <script>
            document.getElementById('manualParticipantForm')?.addEventListener('submit', async function (event) {
                event.preventDefault();
                const form = event.currentTarget;
                const button = form.querySelector('[type="submit"]');
                button.disabled = true;
                try {
                    const response = await fetch(form.action, { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value }, body: new FormData(form) });
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) throw new Error(data.message || Object.values(data.errors || {}).flat()[0] || 'Data peserta gagal disimpan.');
                    form.reset();
                    window.showAdminToast(data.message || 'Data peserta berhasil disimpan.');
                } catch (error) {
                    window.showAdminToast(error.message, 'error');
                } finally {
                    button.disabled = false;
                }
            });
        </script>
    @endif
</body>

</html>
