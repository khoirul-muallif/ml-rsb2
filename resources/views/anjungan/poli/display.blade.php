{{-- resources/views/anjungan/poli/display.blade.php --}}
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Display Antrian — Poliklinik</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">

        {{-- ResponsiveVoice TTS --}}
        <script src="https://code.responsivevoice.org/responsivevoice.js?key={{ config('services.responsivevoice.key') }}"></script>
        <style>
            /* ── Variables ─────────────────────────────────────── */
            :root {
                --g900: #14532d;
                --g800: #166534;
                --g700: #15803d;
                --g600: #16a34a;
                --g500: #22c55e;
                --g400: #4ade80;
                --g300: #86efac;
                --g200: #bbf7d0;
                --g100: #dcfce7;

                --em500: #10b981;
                --em400: #34d399;

                --amber: #f59e0b;
                --amber-lt: #fcd34d;
                --red:   #ef4444;
                --red-lt:#fca5a5;
                --blue:  #3b82f6;

                --white: #ffffff;
                --dark:  #052e16;

                --glass:        rgba(5, 46, 22, 0.65);
                --glass-light:  rgba(255,255,255,0.08);
                --glass-border: rgba(74,222,128,0.20);

                --font-ui:   'Outfit', sans-serif;
                --font-mono: 'JetBrains Mono', monospace;
            }

            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            html, body {
                height: 100%; overflow: hidden;
                font-family: var(--font-ui);
                -webkit-font-smoothing: antialiased;
            }

            /* ── Full-screen Background ─────────────────────────── */
            body {
                background:
                    linear-gradient(160deg,
                        rgba(5,46,22,0.82) 0%,
                        rgba(20,83,45,0.70) 50%,
                        rgba(5,46,22,0.88) 100%
                    ),
                    url("{{ asset('src/bg1.jpeg') }}") center/cover no-repeat fixed;
                color: var(--white);
            }

            /* Subtle grid texture */
            body::after {
                content: '';
                position: fixed; inset: 0; pointer-events: none; z-index: 0;
                background-image:
                    linear-gradient(rgba(74,222,128,.03) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(74,222,128,.03) 1px, transparent 1px);
                background-size: 48px 48px;
            }

            /* ── Loading Overlay ────────────────────────────────── */
            #loading-overlay {
                position: fixed; inset: 0; z-index: 999;
                background: rgba(5,46,22,.95);
                display: flex; flex-direction: column;
                align-items: center; justify-content: center; gap: 20px;
                transition: opacity .5s ease;
            }
            .spin-ring {
                width: 60px; height: 60px;
                border: 4px solid rgba(74,222,128,.2);
                border-top-color: var(--g400);
                border-radius: 50%;
                animation: spin .9s linear infinite;
            }
            @keyframes spin { to { transform: rotate(360deg); } }
            #loading-overlay p {
                color: var(--g300); font-size: 1rem;
                font-weight: 600; letter-spacing: .08em;
            }

            /* ── Unmute Overlay ─────────────────────────────────── */
            #unmute-overlay {
                position: fixed; inset: 0; z-index: 1000;
                background: rgba(5,46,22,.96);
                display: none;
                align-items: center; justify-content: center;
                flex-direction: column; gap: 24px;
            }
            #unmute-overlay .unmute-card {
                background: rgba(255,255,255,.05);
                border: 1px solid var(--glass-border);
                border-radius: 24px;
                padding: 48px 56px;
                text-align: center;
                backdrop-filter: blur(12px);
            }
            #unmute-overlay .unmute-icon { font-size: 4rem; margin-bottom: 16px; }
            #unmute-overlay h2 { font-size: 2rem; font-weight: 800; color: var(--white); margin-bottom: 8px; }
            #unmute-overlay p  { font-size: 1.1rem; color: var(--g300); margin-bottom: 28px; }
            #btn-unmute {
                background: linear-gradient(135deg, var(--g600), var(--g700));
                color: white; border: none;
                padding: 16px 48px; border-radius: 50px;
                font-size: 1.15rem; font-weight: 700;
                font-family: var(--font-ui);
                cursor: pointer;
                box-shadow: 0 4px 24px rgba(34,197,94,.4);
                transition: transform .2s, box-shadow .2s;
            }
            #btn-unmute:hover {
                transform: scale(1.04);
                box-shadow: 0 6px 32px rgba(34,197,94,.55);
            }

            /* ── Root Layout ────────────────────────────────────── */
            .display-root {
                height: 100vh;
                display: grid;
                grid-template-rows: auto 1fr auto;
                padding: 14px 16px 0;
                gap: 12px;
                position: relative; z-index: 1;
            }

            /* ══════════════════════════════════════════════════════
            HEADER BAR
            ══════════════════════════════════════════════════════ */
            .header-bar {
                background: var(--glass);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid var(--glass-border);
                border-radius: 18px;
                padding: 14px 22px;
                display: flex; align-items: center; gap: 18px;
                box-shadow: 0 4px 32px rgba(0,0,0,.35),
                            inset 0 1px 0 rgba(74,222,128,.1);
            }

            /* Logo */
            .logo-wrap {
                width: 72px; height: 72px; flex-shrink: 0;
                background: rgba(255,255,255,.08);
                border: 2px solid rgba(74,222,128,.3);
                border-radius: 16px; overflow: hidden;
                display: flex; align-items: center; justify-content: center;
                box-shadow: 0 0 18px rgba(34,197,94,.2);
            }
            .logo-wrap img { width: 100%; height: 100%; object-fit: contain; padding: 6px; }
            .logo-wrap .logo-fb { font-size: 2.4rem; }

            /* Info poli/dokter */
            .header-info { flex: 1; min-width: 0; }
            .hdr-poli {
                font-size: clamp(1.4rem, 2.5vw, 2rem);
                font-weight: 900; color: var(--white);
                line-height: 1.1; letter-spacing: -.01em;
                text-shadow: 0 2px 8px rgba(0,0,0,.4);
            }
            .hdr-dokter {
                font-size: clamp(1rem, 1.7vw, 1.4rem);
                font-weight: 600; color: var(--g300);
                margin-top: 3px;
            }
            .hdr-meta {
                margin-top: 6px;
                display: flex; gap: 18px; flex-wrap: wrap;
                font-size: .82rem; color: var(--g400);
                font-weight: 500;
            }
            .hdr-meta span { display: flex; align-items: center; gap: 5px; }

            /* Clock */
            .header-clock { text-align: right; flex-shrink: 0; }
            .clock-time {
                font-family: var(--font-mono);
                font-size: clamp(2rem, 3vw, 2.6rem);
                font-weight: 700; color: var(--g400); line-height: 1;
                text-shadow: 0 0 20px rgba(74,222,128,.4);
            }
            /* Live indicator */
            .live-row {
                display: flex; align-items: center;
                justify-content: flex-end; gap: 6px; margin-top: 5px;
            }
            .live-dot {
                width: 7px; height: 7px; border-radius: 50%;
                background: var(--g400);
                box-shadow: 0 0 8px var(--g400);
                animation: blink-live 2s ease-in-out infinite;
            }
            @keyframes blink-live { 0%,100%{opacity:1} 50%{opacity:.2} }
            .clock-date {
                font-size: .78rem; color: var(--amber-lt);
                font-weight: 600; letter-spacing: .03em;
            }

            /* ══════════════════════════════════════════════════════
            MAIN GRID
            ══════════════════════════════════════════════════════ */
            .main-grid {
                display: grid;
                grid-template-columns: 1.15fr 0.85fr;
                gap: 12px;
                min-height: 0;
            }

            /* ── Shared panel base ────────────────────────────── */
            .panel {
                background: var(--glass);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid var(--glass-border);
                border-radius: 18px;
                display: flex; flex-direction: column;
                overflow: hidden;
                box-shadow: 0 8px 40px rgba(0,0,0,.4),
                            inset 0 1px 0 rgba(74,222,128,.08);
            }

            .panel-header {
                padding: 13px 20px;
                background: rgba(5,46,22,.5);
                border-bottom: 1px solid rgba(74,222,128,.15);
                display: flex; align-items: center;
                justify-content: space-between;
            }
            .panel-header-left {
                display: flex; align-items: center; gap: 10px;
            }
            .panel-label {
                font-size: .72rem; font-weight: 800;
                letter-spacing: .12em; text-transform: uppercase;
                color: var(--g400);
            }

            /* Status pill */
            .status-pill {
                display: inline-flex; align-items: center; gap: 6px;
                background: rgba(74,222,128,.1);
                border: 1px solid rgba(74,222,128,.25);
                border-radius: 20px; padding: 3px 12px;
                font-size: .72rem; font-weight: 700;
                color: var(--g400); letter-spacing: .05em;
            }
            .status-dot {
                width: 6px; height: 6px; border-radius: 50%;
                background: var(--g400);
            }

            /* ══════════════════════════════════════════════════════
            PANEL KIRI — ANTRIAN MASUK
            Dominan, besar, mudah dibaca dari jauh
            ══════════════════════════════════════════════════════ */
            .panel-masuk { grid-row: 1; }

            .panel-masuk-body {
                flex: 1;
                display: flex; flex-direction: column;
                align-items: center; justify-content: center;
                padding: 24px 20px;
                position: relative; overflow: hidden;
            }

            /* Background accent */
            .panel-masuk-body::before {
                content: '';
                position: absolute; inset: 0;
                background: radial-gradient(ellipse at center,
                    rgba(34,197,94,.06) 0%, transparent 70%);
                pointer-events: none;
            }

            /* Nomor antrian — BESAR, utama */
            .masuk-card {
                text-align: center; position: relative; z-index: 1;
                width: 100%;
            }

            .nomor-label {
                font-size: .72rem; font-weight: 800;
                letter-spacing: .18em; text-transform: uppercase;
                color: var(--g400); margin-bottom: 8px;
            }

            /* Nomor box */
            .nomor-box {
                background: rgba(255,255,255,.05);
                border: 2px solid rgba(74,222,128,.25);
                border-radius: 20px;
                padding: 20px 32px 16px;
                display: inline-block;
                margin-bottom: 20px;
                box-shadow: 0 0 40px rgba(34,197,94,.1),
                            inset 0 1px 0 rgba(255,255,255,.08);
            }
            .nomor-text {
                font-family: var(--font-mono);
                font-size: clamp(4rem, 9vw, 7.5rem);
                font-weight: 700;
                color: var(--g400);
                line-height: 1;
                letter-spacing: .06em;
                text-shadow: 0 0 30px rgba(74,222,128,.5);
            }

            /* Nama pasien */
            .nama-pasien {
                font-size: clamp(1.6rem, 3vw, 2.4rem);
                font-weight: 800;
                color: var(--white);
                line-height: 1.2;
                margin-bottom: 14px;
                text-shadow: 0 2px 8px rgba(0,0,0,.4);
            }

            /* Poli chip */
            .poli-chip {
                display: inline-flex; align-items: center; gap: 8px;
                background: linear-gradient(135deg, rgba(34,197,94,.2), rgba(16,185,129,.15));
                border: 1px solid rgba(74,222,128,.4);
                border-radius: 30px;
                padding: 8px 22px;
                font-size: 1rem; font-weight: 700;
                color: var(--g300);
                box-shadow: 0 0 16px rgba(34,197,94,.15);
            }

            /* Empty state */
            .masuk-empty {
                text-align: center; position: relative; z-index: 1;
            }
            .empty-icon-lg {
                font-size: 5rem; margin-bottom: 16px; opacity: .2;
                display: block;
            }
            .empty-text {
                font-size: 1.1rem; color: var(--g300);
                font-weight: 500; opacity: .7;
            }

            /* ── AKTIF animations ─────────────────────────────── */
            @keyframes panel-glow {
                0%,100% {
                    box-shadow: 0 8px 40px rgba(0,0,0,.4),
                                inset 0 1px 0 rgba(74,222,128,.08);
                    border-color: var(--glass-border);
                }
                50% {
                    box-shadow: 0 0 60px 10px rgba(239,68,68,.3),
                                inset 0 1px 0 rgba(239,68,68,.12);
                    border-color: rgba(239,68,68,.5);
                }
            }
            @keyframes nomor-glow {
                0%,100% { color: var(--g400); text-shadow: 0 0 30px rgba(74,222,128,.5); }
                50%      { color: #fca5a5;    text-shadow: 0 0 40px rgba(239,68,68,.7); }
            }
            @keyframes nomor-box-flash {
                0%,100% { border-color: rgba(74,222,128,.25); background: rgba(255,255,255,.05); }
                50%      { border-color: rgba(239,68,68,.5);  background: rgba(239,68,68,.08); }
            }
            @keyframes bg-flash {
                0%,100% { background: rgba(5,46,22,.65); }
                50%      { background: rgba(100,10,10,.35); }
            }
            @keyframes scale-pulse {
                0%,100% { transform: scale(1); }
                50%      { transform: scale(1.04); }
            }

            .panel-masuk.aktif {
                animation: panel-glow 1.2s ease-in-out infinite;
            }
            .panel-masuk.aktif .panel-masuk-body {
                animation: bg-flash 1.2s ease-in-out infinite;
            }
            .panel-masuk.aktif .nomor-text {
                animation: nomor-glow 1.2s ease-in-out infinite;
            }
            .panel-masuk.aktif .nomor-box {
                animation: nomor-box-flash 1.2s ease-in-out infinite;
            }
            .panel-masuk.aktif .masuk-card {
                animation: scale-pulse 1.2s ease-in-out infinite;
            }
            .panel-masuk.aktif .status-dot {
                background: var(--red);
                box-shadow: 0 0 8px var(--red);
                animation: blink-live .7s ease-in-out infinite;
            }
            .panel-masuk.aktif .status-pill {
                border-color: rgba(239,68,68,.4);
                color: var(--red-lt);
                background: rgba(239,68,68,.1);
            }

            /* ══════════════════════════════════════════════════════
            PANEL KANAN — DAFTAR TUNGGU
            ══════════════════════════════════════════════════════ */
            .panel-tunggu { }

            .tunggu-count-badge {
                font-family: var(--font-mono);
                font-size: .82rem; font-weight: 700;
                background: rgba(34,197,94,.15);
                border: 1px solid rgba(74,222,128,.3);
                border-radius: 12px;
                padding: 3px 12px;
                color: var(--g400);
            }

            .tunggu-list {
                flex: 1; overflow-y: auto; padding: 10px;
            }
            .tunggu-list::-webkit-scrollbar { width: 5px; }
            .tunggu-list::-webkit-scrollbar-track { background: transparent; }
            .tunggu-list::-webkit-scrollbar-thumb {
                background: rgba(74,222,128,.3); border-radius: 4px;
            }

            /* Each waiting patient row */
            .tunggu-item {
                display: flex; align-items: center; gap: 14px;
                padding: 11px 16px;
                border-radius: 12px;
                background: rgba(255,255,255,.04);
                border: 1px solid rgba(74,222,128,.1);
                margin-bottom: 7px;
                transition: all .2s;
                cursor: default;
            }
            .tunggu-item:hover {
                background: rgba(34,197,94,.1);
                border-color: rgba(74,222,128,.3);
                transform: translateX(4px);
            }
            .tunggu-item:last-child { margin-bottom: 0; }

            /* Row number */
            .tunggu-urut {
                font-size: .72rem;
                color: var(--g400); opacity: .5;
                font-family: var(--font-mono);
                min-width: 20px;
            }

            .tunggu-nomor {
                font-family: var(--font-mono);
                font-size: 1.05rem; font-weight: 700;
                color: var(--g400);
                min-width: 80px; flex-shrink: 0;
            }

            .tunggu-divider {
                width: 1px; height: 28px;
                background: rgba(74,222,128,.2); flex-shrink: 0;
            }

            .tunggu-nama {
                font-size: .95rem; font-weight: 600;
                color: var(--white);
                white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
                flex: 1;
            }

            /* Tunggu empty */
            .tunggu-empty {
                flex: 1; display: flex; flex-direction: column;
                align-items: center; justify-content: center;
                padding: 40px 20px; text-align: center;
            }
            .tunggu-empty .empty-icon { font-size: 3rem; opacity: .2; margin-bottom: 12px; }
            .tunggu-empty p { font-size: .9rem; color: var(--g300); opacity: .7; font-weight: 500; }

            /* ══════════════════════════════════════════════════════
            FOOTER
            ══════════════════════════════════════════════════════ */
            .footer-bar {
                height: 70px; overflow: hidden;
                border-radius: 16px 16px 0 0;
            }
            .footer-bar img { width: 100%; height: 100%; object-fit: cover; }
            .footer-fallback {
                height: 70px;
                background: var(--glass);
                backdrop-filter: blur(12px);
                border: 1px solid var(--glass-border);
                border-bottom: none;
                border-radius: 16px 16px 0 0;
                display: flex; align-items: center; justify-content: center; gap: 14px;
            }
            .footer-fallback .f-dot {
                width: 5px; height: 5px; border-radius: 50%;
                background: var(--g400); opacity: .4;
            }
            .footer-fallback span {
                font-size: .72rem; color: var(--g300);
                letter-spacing: .15em; text-transform: uppercase; font-weight: 600;
            }

            /* ── Utility ────────────────────────────────────────── */
            @keyframes fade-up {
                from { opacity:0; transform:translateY(12px); }
                to   { opacity:1; transform:translateY(0); }
            }
            .header-bar { animation: fade-up .4s ease both; }
            .main-grid  { animation: fade-up .4s .1s ease both; }
        </style>
    </head>
    <body>

        {{-- Loading Overlay --}}
        <div id="loading-overlay">
            <div class="spin-ring"></div>
            <p>Memuat data antrian...</p>
        </div>

        {{-- Unmute Overlay --}}
        <div id="unmute-overlay">
            <div class="unmute-card">
                <div class="unmute-icon">🔊</div>
                <h2>Aktifkan Suara</h2>
                <p>Klik tombol di bawah untuk mengaktifkan panggilan antrian</p>
                <button id="btn-unmute">🔊 Aktifkan Suara</button>
            </div>
        </div>

        <div class="display-root">

            {{-- ── Header ── --}}
            <div class="header-bar">
                <div class="logo-wrap">
                    <img id="img-logo" src="" alt="Logo RS" style="display:none"
                        onerror="this.style.display='none'; document.getElementById('logo-fb').style.display='block'">
                    <span id="logo-fb" class="logo-fb">🏥</span>
                </div>

                <div class="header-info">
                    <div class="hdr-poli"   id="txt-nm-poli">—</div>
                    <div class="hdr-dokter" id="txt-nm-dokter">—</div>
                    <div class="hdr-meta">
                        <span>🕐 <span id="txt-jam-praktek">—</span></span>
                        <span id="txt-keterangan-wrap" style="display:none">
                            ℹ️ <span id="txt-keterangan">—</span>
                        </span>
                    </div>
                </div>

                <div class="header-clock">
                    <div class="clock-time" id="clock-time">—</div>
                    <div class="live-row">
                        <div class="live-dot"></div>
                        <div class="clock-date" id="clock-date">—</div>
                    </div>
                </div>
            </div>

            {{-- ── Main Grid ── --}}
            <div class="main-grid">

                {{-- ── PANEL KIRI: Antrian Masuk ── --}}
                <div class="panel panel-masuk" id="panel-masuk">
                    <div class="panel-header">
                        <div class="panel-header-left">
                            <div class="panel-label">🏥 Antrian Masuk</div>
                        </div>
                        <div class="status-pill" id="status-pill">
                            <div class="status-dot" id="status-dot"></div>
                            <span id="status-text">Menunggu</span>
                        </div>
                    </div>

                    <div class="panel-masuk-body" id="panel-masuk-body">
                        <div class="masuk-empty">
                            <span class="empty-icon-lg">🏥</span>
                            <p class="empty-text">Belum ada pasien dipanggil</p>
                        </div>
                    </div>
                </div>

                {{-- ── PANEL KANAN: Daftar Tunggu ── --}}
                <div class="panel panel-tunggu">
                    <div class="panel-header">
                        <div class="panel-header-left">
                            <div class="panel-label">📋 Daftar Tunggu</div>
                        </div>
                        <span class="tunggu-count-badge" id="tunggu-count">0</span>
                    </div>

                    <div class="tunggu-list" id="tunggu-list">
                        <div class="tunggu-empty">
                            <div class="empty-icon">⏳</div>
                            <p>Tidak ada pasien menunggu</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="footer-bar" id="footer-bar">
                <img src="{{ asset('src/footbanner.png') }}" alt="Footer"
                    onerror="handleFooterError(this)">
            </div>

        </div>

        <script>
            // ── Constants ─────────────────────────────────────────────
            const ENC_POLI   = @json($encPoli);
            const ENC_DOKTER = @json($encDokter);
            const API_URL    = "{{ route('anjungan.poli.api.antrian') }}";
            const ACK_URL    = "{{ route('anjungan.poli.api.ack') }}";
            const CSRF       = document.querySelector('meta[name="csrf-token"]').content;

            // ── State ─────────────────────────────────────────────────
            let isSpeaking      = false;
            let isFirstLoad     = true;
            let audioUnlocked   = false;
            let pendingSpeech   = null;
            let lastPasien      = null;  // pasien terakhir dipanggil, tetap ditampilkan
            let isAcknowledging = false; // guard: jangan render pasien baru saat ACK belum selesai

            // ── Unmute handler ────────────────────────────────────────
            // FIX: Hapus silent speech (penyebab CORS error) — langsung set flag saja.
            // Browser sudah unlock audio context lewat gesture klik tombol ini.
            const unmuteOverlay = document.getElementById('unmute-overlay');
            document.getElementById('btn-unmute')?.addEventListener('click', () => {
                audioUnlocked = true;
                unmuteOverlay.style.display = 'none';
                // Kalau ada speech yang tertunda, mainkan sekarang
                if (pendingSpeech) {
                    bunyikanPanggilan(pendingSpeech);
                    pendingSpeech = null;
                }
            });

            // ── Clock ─────────────────────────────────────────────────
            function tickClock() {
                const now = new Date();
                document.getElementById('clock-time').textContent =
                    now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                document.getElementById('clock-date').textContent =
                    now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            }
            tickClock();
            setInterval(tickClock, 1000);

            // ── Fetch ─────────────────────────────────────────────────
            async function fetchAntrian() {
                try {
                    const resp = await fetch(
                        `${API_URL}?poli=${encodeURIComponent(ENC_POLI)}&dokter=${encodeURIComponent(ENC_DOKTER)}`
                    );
                    if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
                    const data = await resp.json();
                    renderHeader(data.info);
                    renderMasuk(data.masuk);
                    renderTunggu(data.tunggu);

                    if (isFirstLoad) {
                        const ov = document.getElementById('loading-overlay');
                        ov.style.opacity = '0';
                        setTimeout(() => ov.style.display = 'none', 500);
                        isFirstLoad = false;
                    }
                } catch (err) {
                    console.error('Fetch error:', err);
                }
            }

            // ── Render header ─────────────────────────────────────────
            function renderHeader(info) {
                if (info?.logo_base64) {
                    const img = document.getElementById('img-logo');
                    const fb  = document.getElementById('logo-fb');
                    img.src = info.logo_base64;
                    img.style.display = 'block';
                    fb.style.display  = 'none';
                }
                document.getElementById('txt-nm-poli').textContent    = info?.nm_poli    || '—';
                document.getElementById('txt-nm-dokter').textContent  = info?.nm_dokter  || '—';
                document.getElementById('txt-jam-praktek').textContent= info?.jam_praktek|| '—';
                if (info?.keterangan) {
                    document.getElementById('txt-keterangan').textContent = info.keterangan;
                    document.getElementById('txt-keterangan-wrap').style.display = '';
                }
            }

            // ── Helper: selalu render kartu pasien ke body ───────────
            function renderKartuPasien(p) {
                document.getElementById('panel-masuk-body').innerHTML = `
                    <div class="masuk-card">
                        <div class="nomor-label">Nomor Antrian</div>
                        <div class="nomor-box">
                            <div class="nomor-text">${h(p.no_antrian)}</div>
                        </div>
                        <div class="nama-pasien">${h(p.nm_pasien)}</div>
                        ${p.nm_poli ? `<div class="poli-chip">📍 ${h(p.nm_poli)}</div>` : ''}
                    </div>`;
            }

            // ── Render antrian masuk ──────────────────────────────────
            function renderMasuk(masukList) {
                const panel      = document.getElementById('panel-masuk');
                const statusText = document.getElementById('status-text');

                // ── KASUS A: Server tidak ada antrian aktif (sudah di-ACK) ──
                if (!masukList?.length) {
                    if (lastPasien) {
                        // Selalu render ulang eksplisit — jangan andalkan body lama
                        renderKartuPasien(lastPasien);
                        panel.classList.remove('aktif');
                        statusText.textContent = 'Sudah Dipanggil';
                    } else {
                        document.getElementById('panel-masuk-body').innerHTML = `
                            <div class="masuk-empty">
                                <span class="empty-icon-lg">🏥</span>
                                <p class="empty-text">Belum ada pasien dipanggil</p>
                            </div>`;
                        panel.classList.remove('aktif');
                        statusText.textContent = 'Menunggu';
                    }
                    return;
                }

                // ── KASUS B: Ada antrian aktif dari server ──
                const p           = masukList[0];
                const isNewPasien = !lastPasien || lastPasien.no_antrian !== p.no_antrian;

                renderKartuPasien(p);   // selalu render (data fresh)
                lastPasien = p;         // simpan sebagai pasien terakhir

                if (isNewPasien && !isSpeaking && !isAcknowledging) {
                    // Pasien BARU dan tidak sedang proses ACK → glow + TTS (satu kali)
                    panel.classList.add('aktif');
                    statusText.textContent = 'Memanggil...';
                    bunyikanPanggilan(p);
                } else if (!isNewPasien || isAcknowledging) {
                    // Pasien sama ATAU sedang ACK → jangan ulang suara
                    panel.classList.remove('aktif');
                    statusText.textContent = 'Sudah Dipanggil';
                }
            }

            // ── Render daftar tunggu ──────────────────────────────────
            function renderTunggu(tungguList) {
                const list  = document.getElementById('tunggu-list');
                const count = document.getElementById('tunggu-count');
                count.textContent = tungguList?.length ?? 0;

                if (!tungguList?.length) {
                    list.innerHTML = `
                        <div class="tunggu-empty">
                            <div class="empty-icon">⏳</div>
                            <p>Tidak ada pasien menunggu</p>
                        </div>`;
                    return;
                }

                list.innerHTML = tungguList.map((p, i) => `
                    <div class="tunggu-item">
                        <div class="tunggu-urut">${i + 1}</div>
                        <div class="tunggu-nomor">${h(p.no_antrian)}</div>
                        <div class="tunggu-divider"></div>
                        <div class="tunggu-nama">${h(p.nm_pasien)}</div>
                    </div>`).join('');
            }

            // ── TTS ───────────────────────────────────────────────────
            function bunyikanPanggilan(pasien) {
                // Guard: jangan pernah dobel
                if (isSpeaking) {
                    console.warn('bunyikanPanggilan dipanggil saat isSpeaking=true, diabaikan');
                    return;
                }
                if (!window.responsiveVoice || !responsiveVoice.voiceSupport()) {
                    onSelesaiBicara(); return;
                }
                if (!audioUnlocked) {
                    pendingSpeech = pasien;
                    unmuteOverlay.style.display = 'flex';
                    return;
                }

                // Set true SEBELUM speak() agar race condition tidak lolos
                isSpeaking = true;
                pendingSpeech = null;

                const teks = `Antrian nomor ${pasien.no_antrian.toLowerCase()}, ` +
                            `atas nama ${pasien.nm_pasien.toLowerCase()}, ` +
                            `silahkan ke ${(pasien.nm_poli ?? '').toLowerCase()}`;

                console.log('[TTS] Speaking:', teks);

                // Safety timeout 20 detik
                const safetyTimer = setTimeout(() => {
                    console.warn('[TTS] Safety timeout — force onSelesaiBicara');
                    onSelesaiBicara();
                }, 20000);

                responsiveVoice.speak(teks, 'Indonesian Female', {
                    pitch: 1, rate: 0.9, volume: 1,
                    onstart: () => console.log('[TTS] onstart fired'),
                    onend: () => {
                        console.log('[TTS] onend fired — normal');
                        clearTimeout(safetyTimer);
                        onSelesaiBicara();
                    },
                    onerror: (err) => {
                        console.error('[TTS] onerror:', err);
                        clearTimeout(safetyTimer);
                        if (err?.toString().includes('NotAllowedError')) {
                            isSpeaking = false; // reset dulu sebelum re-prompt
                            audioUnlocked = false;
                            pendingSpeech = pasien;
                            unmuteOverlay.style.display = 'flex';
                            return; // jangan panggil onSelesaiBicara — nanti setelah unmute
                        }
                        onSelesaiBicara();
                    },
                });
            }

            function onSelesaiBicara() {
                if (!isSpeaking) {
                    console.warn('[TTS] onSelesaiBicara dipanggil saat isSpeaking sudah false — diabaikan');
                    return;
                }
                isSpeaking = false;
                console.log('[TTS] onSelesaiBicara — reset state, kirim ACK');
                // Hapus animasi glow saja — tampilan pasien TETAP terlihat
                document.getElementById('panel-masuk').classList.remove('aktif');
                document.getElementById('status-text').textContent = 'Sudah Dipanggil';
                acknowledge();
            }

            // ── Acknowledge ───────────────────────────────────────────
            // FIX: isAcknowledging mencegah double-trigger saat fetch interval
            // bertabrakan dengan ACK yang baru dikirim.
            async function acknowledge() {
                isAcknowledging = true;
                console.log('[ACK] Sending acknowledge...');
                try {
                    const r = await fetch(ACK_URL, {
                        method : 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                        body   : JSON.stringify({ poli: ENC_POLI, dokter: ENC_DOKTER }),
                    });
                    console.log('[ACK] Server responded:', r.status);
                } catch (err) {
                    console.error('[ACK] Error:', err);
                } finally {
                    // Delay 3 detik sebelum buka kunci:
                    // - cukup lama untuk server selesai update DB
                    // - cukup lama untuk fetch interval berikutnya tidak tertipu status lama
                    setTimeout(() => {
                        isAcknowledging = false;
                        console.log('[ACK] Lock released — siap terima pasien berikutnya');
                    }, 3000);
                }
            }

            // ── Footer fallback ───────────────────────────────────────
            function handleFooterError(img) {
                if (!img.dataset.tried) {
                    img.dataset.tried = '1';
                    img.src = "{{ asset('images/footbanner.png') }}";
                    return;
                }
                img.style.display = 'none';
                document.getElementById('footer-bar').innerHTML = `
                    <div class="footer-fallback">
                        <div class="f-dot"></div>
                        <span>Sistem Informasi Manajemen Rumah Sakit</span>
                        <div class="f-dot"></div>
                    </div>`;
            }

            // ── XSS escape ────────────────────────────────────────────
            function h(s) {
                const d = document.createElement('div');
                d.appendChild(document.createTextNode(s ?? ''));
                return d.innerHTML;
            }

            // ── Init ──────────────────────────────────────────────────
            fetchAntrian();
            setInterval(fetchAntrian, 10_000);
        </script>
    </body>
</html>