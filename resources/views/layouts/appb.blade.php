{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Antrian') — RSB</title>

    {{-- Fonts: Plus Jakarta Sans (UI) + DM Mono (nomor/kode) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ── Design Tokens ───────────────────────────────────── */
        :root {
            --navy-950 : #060d1a;
            --navy-900 : #0b1628;
            --navy-800 : #0f2040;
            --navy-700 : #163058;
            --navy-600 : #1e4070;
            --teal-400 : #2dd4bf;
            --teal-300 : #5eead4;
            --teal-500 : #14b8a6;
            --amber-400: #fbbf24;
            --amber-300: #fcd34d;
            --rose-500 : #f43f5e;
            --rose-400 : #fb7185;
            --slate-400: #94a3b8;
            --slate-300: #cbd5e1;
            --slate-200: #e2e8f0;
            --white    : #ffffff;

            --font-ui  : 'Plus Jakarta Sans', sans-serif;
            --font-mono: 'DM Mono', monospace;

            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 16px;

            --shadow-sm : 0 1px 3px rgba(0,0,0,.4), 0 1px 2px rgba(0,0,0,.3);
            --shadow-md : 0 4px 12px rgba(0,0,0,.5);
            --shadow-lg : 0 8px 32px rgba(0,0,0,.6);
            --shadow-teal: 0 0 24px rgba(45,212,191,.25);
        }

        /* ── Reset & Base ────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-ui);
            background-color: var(--navy-950);
            color: var(--slate-300);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        /* Subtle grid background texture */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(45,212,191,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(45,212,191,.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        main { position: relative; z-index: 1; }

        a { text-decoration: none; color: inherit; }
        button { font-family: inherit; cursor: pointer; }
        input, select, textarea { font-family: inherit; }

        /* ── Scrollbar ───────────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--navy-900); }
        ::-webkit-scrollbar-thumb { background: var(--navy-600); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--teal-500); }

        /* ── Toast ───────────────────────────────────────────── */
        .toast-rs {
            position: fixed;
            bottom: 24px; right: 24px;
            background: var(--navy-800);
            border: 1px solid var(--navy-600);
            color: var(--white);
            padding: 14px 18px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            display: flex; align-items: center; gap: 10px;
            font-size: .875rem; font-weight: 500;
            animation: toast-in .25s cubic-bezier(.34,1.56,.64,1);
            max-width: 340px;
        }
        .toast-rs.toast-success { border-left: 3px solid var(--teal-400); }
        .toast-rs.toast-error   { border-left: 3px solid var(--rose-500); }
        .toast-rs.toast-warning { border-left: 3px solid var(--amber-400); }
        .toast-rs.toast-info    { border-left: 3px solid #60a5fa; }
        .toast-rs .toast-icon   { font-size: 1rem; flex-shrink: 0; }

        @keyframes toast-in {
            from { transform: translateY(20px) scale(.95); opacity: 0; }
            to   { transform: translateY(0) scale(1);      opacity: 1; }
        }
        @keyframes toast-out {
            from { transform: translateY(0); opacity: 1; }
            to   { transform: translateY(8px); opacity: 0; }
        }

        /* ── Loading Spinner ─────────────────────────────────── */
        .spin {
            display: inline-block;
            width: 18px; height: 18px;
            border: 2px solid rgba(45,212,191,.3);
            border-top-color: var(--teal-400);
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Card base ───────────────────────────────────────── */
        .card-rs {
            background: var(--navy-900);
            border: 1px solid var(--navy-700);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
        }
        .card-rs-header {
            padding: 12px 18px;
            border-bottom: 1px solid var(--navy-700);
            display: flex; align-items: center; gap: 8px;
            font-weight: 700; font-size: .875rem;
            letter-spacing: .04em; text-transform: uppercase;
            color: var(--slate-400);
        }
        .card-rs-header .dot {
            width: 8px; height: 8px; border-radius: 50%;
            flex-shrink: 0;
        }

        /* ── Badge ───────────────────────────────────────────── */
        .badge-rs {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 9px; border-radius: 20px;
            font-size: .73rem; font-weight: 600; letter-spacing: .03em;
        }

        /* ── Utilities ───────────────────────────────────────── */
        .mono { font-family: var(--font-mono); }
        .text-teal   { color: var(--teal-400) !important; }
        .text-amber  { color: var(--amber-400) !important; }
        .text-muted-rs { color: var(--slate-400); }
    </style>

    @stack('styles')
</head>
<body>
    <main>
        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ── Toast Helper ──────────────────────────────────────
        function showToast(message, type = 'info', duration = 3500) {
            const icons = {
                success: 'fa-circle-check',
                error  : 'fa-circle-xmark',
                warning: 'fa-triangle-exclamation',
                info   : 'fa-circle-info',
            };
            const el = document.createElement('div');
            el.className = `toast-rs toast-${type}`;
            el.innerHTML = `
                <i class="fa-solid ${icons[type] ?? icons.info} toast-icon"></i>
                <span>${message}</span>`;
            document.body.appendChild(el);
            setTimeout(() => {
                el.style.animation = 'toast-out .2s ease forwards';
                setTimeout(() => el.remove(), 200);
            }, duration);
        }

        // ── Format helpers ────────────────────────────────────
        const formatTime = t => t ? t.substring(0, 5) : '—';
        const formatNumber = n => String(n).padStart(3, '0');
    </script>

    @stack('scripts')
</body>
</html>