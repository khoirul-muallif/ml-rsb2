{{-- resources/views/anjungan/poli/jadwal.blade.php --}}
@extends('layouts.app')

@section('title', 'Jadwal Praktek Dokter')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    /* ── Reset & Root ───────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; }

    :root {
        --green-950: #052e16;
        --green-900: #14532d;
        --green-800: #166534;
        --green-700: #15803d;
        --green-600: #16a34a;
        --green-500: #22c55e;
        --green-400: #4ade80;
        --green-300: #86efac;
        --green-200: #bbf7d0;
        --emerald-500: #10b981;
        --emerald-400: #34d399;
        --emerald-300: #6ee7b7;
        --teal-500:   #14b8a6;
        --teal-400:   #2dd4bf;
        --white:      #ffffff;
        --slate-50:   #f8fafc;
        --slate-100:  #f1f5f9;
        --slate-200:  #e2e8f0;
        --slate-300:  #cbd5e1;
        --slate-400:  #94a3b8;
        --slate-500:  #64748b;
        --amber-400:  #fbbf24;
        --amber-300:  #fcd34d;

        --font-ui:   'Plus Jakarta Sans', sans-serif;
        --font-mono: 'JetBrains Mono', monospace;

        /* Glass surfaces */
        --glass-bg:     rgba(5, 46, 22, 0.72);
        --glass-border: rgba(74, 222, 128, 0.18);
        --glass-hover:  rgba(22, 101, 52, 0.65);
        --glass-header: rgba(5, 46, 22, 0.85);
    }

    html, body {
        margin: 0; padding: 0;
        font-family: var(--font-ui);
        min-height: 100vh;
    }

    /* ── Full-page background ───────────────────────────── */
    body {
        background:
            linear-gradient(
                135deg,
                rgba(9, 78, 38, 0.88) 0%,
                rgba(23, 109, 57, 0.75) 40%,
                rgba(17, 146, 71, 0.92) 100%
            ),
            url("{{ asset('src/bg1.jpeg') }}") center/cover no-repeat fixed;
    }

    /* ── Page wrapper ───────────────────────────────────── */
    .jadwal-root {
        max-width: 1280px;
        margin: 0 auto;
        padding: 24px 20px 100px;
    }

    /* ── Header RS ──────────────────────────────────────── */
    .rs-header {
        background: var(--glass-header);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 18px 24px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 18px;
        box-shadow:
            0 4px 32px rgba(0,0,0,0.4),
            inset 0 1px 0 rgba(74,222,128,0.12);
    }

    .rs-header-logo-wrap {
        width: 72px; height: 72px;
        flex-shrink: 0;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(74,222,128,0.25);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(34,197,94,0.2);
    }
    .rs-header-logo-wrap img {
        width: 100%; height: 100%;
        object-fit: contain; padding: 6px;
    }

    /* Green cross fallback */
    .logo-fallback {
        font-size: 2.2rem; color: var(--green-400);
    }

    .rs-header-info { flex: 1; min-width: 0; }

    .rs-name {
        font-size: 1.45rem; font-weight: 800;
        color: var(--white); line-height: 1.2;
        letter-spacing: -0.01em;
        text-shadow: 0 2px 8px rgba(0,0,0,0.5);
    }
    .rs-address {
        font-size: .8rem; color: var(--green-300);
        margin-top: 4px; opacity: 0.85;
    }

    .rs-datetime { text-align: right; flex-shrink: 0; }
    .rs-datetime .time {
        font-family: var(--font-mono);
        font-size: 2rem; font-weight: 600;
        color: var(--green-400); line-height: 1;
        text-shadow: 0 0 20px rgba(74,222,128,0.5);
    }
    .rs-datetime .date {
        font-size: .78rem; color: var(--amber-300);
        font-weight: 600; margin-top: 4px;
        letter-spacing: .04em;
    }

    /* Pulse dot */
    .rs-datetime .live-dot {
        display: inline-block;
        width: 7px; height: 7px;
        border-radius: 50%;
        background: var(--green-400);
        margin-right: 5px;
        animation: pulse-dot 2s ease-in-out infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .4; transform: scale(.7); }
    }

    /* ── Divider / green glow line ──────────────────────── */
    .glow-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--green-500), var(--emerald-400), transparent);
        margin: 0 0 18px;
        opacity: .5;
    }

    /* ── Toolbar ─────────────────────────────────────────── */
    .toolbar {
        display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap;
        gap: 12px; margin-bottom: 14px;
    }
    .toolbar-title {
        font-size: 1.05rem; font-weight: 800;
        color: var(--white);
        display: flex; align-items: center; gap: 10px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.4);
    }
    .hari-badge {
        background: linear-gradient(135deg, rgba(34,197,94,.2), rgba(16,185,129,.15));
        border: 1px solid rgba(74,222,128,.4);
        color: var(--green-400);
        font-size: .72rem; font-weight: 800;
        padding: 4px 12px; border-radius: 20px;
        letter-spacing: .1em;
        text-shadow: 0 0 12px rgba(74,222,128,0.5);
        box-shadow: 0 0 10px rgba(34,197,94,.15);
    }

    .per-page-wrap {
        display: flex; align-items: center; gap: 8px;
        font-size: .82rem; color: var(--green-300);
    }
    .per-page-wrap select {
        background: rgba(5, 46, 22, 0.7);
        border: 1px solid rgba(74,222,128,.3);
        color: var(--white);
        padding: 6px 12px; border-radius: 10px;
        font-size: .82rem; font-family: var(--font-ui);
        cursor: pointer; outline: none;
        backdrop-filter: blur(8px);
        transition: border-color .2s, box-shadow .2s;
    }
    .per-page-wrap select:focus {
        border-color: var(--green-500);
        box-shadow: 0 0 0 3px rgba(34,197,94,.15);
    }

    /* ── Table wrapper ───────────────────────────────────── */
    .table-wrap {
        background: var(--glass-bg);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow:
            0 8px 48px rgba(0,0,0,0.5),
            inset 0 1px 0 rgba(74,222,128,.1);
    }

    .jadwal-table { width: 100%; border-collapse: collapse; }

    .jadwal-table thead th {
        background: rgba(5, 46, 22, 0.8);
        padding: 13px 16px;
        font-size: .68rem; font-weight: 800;
        letter-spacing: .1em; text-transform: uppercase;
        color: var(--green-400);
        border-bottom: 1px solid rgba(74,222,128,.2);
        white-space: nowrap;
    }
    .jadwal-table thead th:first-child { text-align: center; }

    /* Subtle header glow line */
    .jadwal-table thead tr {
        box-shadow: inset 0 -1px 0 rgba(74,222,128,.25);
    }

    /* ── Rows ─────────────────────────────────────────────── */
    .jadwal-table tbody tr {
        border-bottom: 1px solid rgba(74,222,128,.07);
        transition: background .15s, transform .1s;
    }
    .jadwal-table tbody tr:last-child { border-bottom: none; }
    .jadwal-table tbody tr:hover {
        background: var(--glass-hover);
    }

    /* Alternating subtle tint */
    .jadwal-table tbody tr:nth-child(even) {
        background: rgba(22, 101, 52, 0.12);
    }
    .jadwal-table tbody tr:nth-child(even):hover {
        background: var(--glass-hover);
    }

    .jadwal-table tbody td {
        padding: 13px 16px;
        font-size: .875rem; vertical-align: middle;
        color: var(--slate-200);
    }
    .jadwal-table tbody td:first-child {
        text-align: center;
        color: var(--green-400);
        font-family: var(--font-mono); font-size: .78rem;
        opacity: .7;
    }

    /* ── Cell styles ─────────────────────────────────────── */
    .td-dokter {
        font-weight: 700; color: var(--white);
        font-size: .9rem;
    }
    .td-poli {
        color: var(--green-300);
        font-size: .85rem;
    }
    .td-jam {
        font-family: var(--font-mono);
        font-size: .88rem; font-weight: 500;
        text-align: center;
    }
    .td-jam.mulai   { color: var(--amber-300); }
    .td-jam.selesai { color: var(--emerald-400); }

    .td-register {
        text-align: center;
        font-family: var(--font-mono);
        font-weight: 600; color: var(--white);
        font-size: .88rem;
    }

    /* Register count badge */
    .register-badge {
        display: inline-block;
        background: rgba(34,197,94,.12);
        border: 1px solid rgba(74,222,128,.25);
        border-radius: 8px;
        padding: 2px 10px;
        color: var(--green-300);
        font-family: var(--font-mono);
        font-size: .82rem; font-weight: 600;
    }

    .td-aksi { text-align: center; white-space: nowrap; }

    /* ── Action buttons ──────────────────────────────────── */
    .btn-inline {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 12px; border-radius: 9px;
        font-size: .74rem; font-weight: 700;
        text-decoration: none; border: 1px solid transparent;
        transition: all .2s;
        line-height: 1; font-family: var(--font-ui);
        letter-spacing: .03em;
        cursor: pointer;
    }

    /* Display — blue/teal */
    .btn-inline-display {
        background: rgba(20, 184, 166, 0.15);
        border-color: rgba(45, 212, 191, 0.35);
        color: var(--teal-400);
        box-shadow: 0 0 10px rgba(20,184,166,.1);
    }
    .btn-inline-display:hover {
        background: rgba(20, 184, 166, 0.28);
        border-color: rgba(45, 212, 191, 0.6);
        color: #fff;
        box-shadow: 0 0 18px rgba(20,184,166,.3);
        transform: translateY(-1px);
    }

    /* Panggil — green */
    .btn-inline-pemanggil {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(74, 222, 128, 0.35);
        color: var(--green-400);
        margin-left: 6px;
        box-shadow: 0 0 10px rgba(34,197,94,.1);
    }
    .btn-inline-pemanggil:hover {
        background: rgba(34, 197, 94, 0.28);
        border-color: rgba(74, 222, 128, 0.6);
        color: #fff;
        box-shadow: 0 0 18px rgba(34,197,94,.35);
        transform: translateY(-1px);
    }

    .btn-icon { font-size: .85rem; }

    /* ── Pagination bar ───────────────────────────────────── */
    .pager-bar {
        display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap;
        gap: 10px; padding: 14px 18px;
        background: rgba(5, 46, 22, 0.6);
        border-top: 1px solid rgba(74,222,128,.15);
    }
    .pager-info {
        font-size: .8rem; color: var(--green-300);
    }
    .pager-info strong { color: var(--white); }

    /* ── Bootstrap 5 pagination — full override ─────────── */
    nav[aria-label="pagination"],
    nav[aria-label="Pagination"] { display: block; }

    .pagination {
        margin: 0;
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: wrap;
        align-items: center;
        gap: 4px;
        list-style: none !important;
        padding-left: 0 !important;
    }

    /* Kill any rogue bullet/list rendering */
    .pagination li { list-style: none !important; }

    .pagination .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        border-radius: 9px !important;
        background: rgba(5, 46, 22, 0.7);
        border: 1px solid rgba(74,222,128,.2) !important;
        color: var(--green-300);
        font-size: .82rem;
        font-family: var(--font-ui);
        font-weight: 600;
        backdrop-filter: blur(8px);
        transition: all .15s;
        text-decoration: none;
        line-height: 1;
    }
    .pagination .page-link:hover {
        background: rgba(34,197,94,.2);
        border-color: rgba(74,222,128,.5) !important;
        color: var(--white);
        box-shadow: 0 0 10px rgba(34,197,94,.2);
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--green-600), var(--green-700)) !important;
        border-color: var(--green-500) !important;
        color: var(--white) !important;
        font-weight: 700;
        box-shadow: 0 0 14px rgba(34,197,94,.4);
    }
    .pagination .page-item.disabled .page-link {
        background: rgba(5, 46, 22, 0.3) !important;
        border-color: rgba(74,222,128,.08) !important;
        color: rgba(134,239,172,.25) !important;
        pointer-events: none;
    }

    /* ── Empty state ──────────────────────────────────────── */
    .empty-row td {
        padding: 60px 20px !important;
        text-align: center;
        color: var(--green-300) !important;
        cursor: default !important;
    }
    .empty-row:hover { background: transparent !important; }
    .empty-icon-lg {
        font-size: 3rem; display: block;
        margin-bottom: 12px; opacity: .4;
    }
    .empty-text {
        font-size: .9rem; opacity: .7;
    }

    /* ── Footer banner ────────────────────────────────────── */
    .footer-fixed {
        position: fixed; bottom: 0; left: 0; width: 100%; z-index: 10;
        height: 64px; overflow: hidden;
    }
    .footer-fixed img { width: 100%; height: 100%; object-fit: cover; }
    .footer-fallback {
        height: 64px;
        background: rgba(5, 46, 22, 0.95);
        backdrop-filter: blur(12px);
        border-top: 1px solid rgba(74,222,128,.2);
        display: flex; align-items: center; justify-content: center;
        gap: 12px;
    }
    .footer-fallback .footer-dot {
        width: 5px; height: 5px; border-radius: 50%;
        background: var(--green-400); opacity: .5;
    }
    .footer-fallback span {
        font-size: .72rem; color: var(--green-300);
        letter-spacing: .15em; text-transform: uppercase;
        font-weight: 600;
    }

    /* ── Decorative green glow orbs (background atmosphere) ── */
    .bg-orb {
        position: fixed;
        border-radius: 50%;
        filter: blur(80px);
        pointer-events: none;
        z-index: 0;
    }
    .bg-orb-1 {
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(34,197,94,.12) 0%, transparent 70%);
        top: -100px; right: -100px;
    }
    .bg-orb-2 {
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(16,185,129,.1) 0%, transparent 70%);
        bottom: 100px; left: -80px;
    }

    /* Ensure content above orbs */
    .jadwal-root { position: relative; z-index: 1; }

    /* ── Animations ───────────────────────────────────────── */
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .rs-header  { animation: fade-up .5s ease both; }
    .toolbar    { animation: fade-up .5s .1s ease both; }
    .table-wrap { animation: fade-up .5s .18s ease both; }

    /* Staggered row entrance */
    .jadwal-table tbody tr {
        animation: fade-up .35s ease both;
    }
    @for ($i = 1; $i <= 15; $i++)
        .jadwal-table tbody tr:nth-child({{ $i }}) {
            animation-delay: {{ 0.2 + ($i * 0.04) }}s;
        }
    @endfor

    /* ── Responsive ───────────────────────────────────────── */
    @media (max-width: 768px) {
        .rs-header { flex-wrap: wrap; }
        .rs-datetime { width: 100%; text-align: left; }
        .jadwal-table { font-size: .78rem; }
        .jadwal-table thead th,
        .jadwal-table tbody td { padding: 10px 10px; }
        .btn-inline { padding: 5px 8px; font-size: .68rem; }
    }
</style>
@endpush

@section('content')

{{-- Decorative background orbs --}}
<div class="bg-orb bg-orb-1"></div>
<div class="bg-orb bg-orb-2"></div>

<div class="jadwal-root">

    {{-- ── Header RS ── --}}
    <div class="rs-header">
        <div class="rs-header-logo-wrap">
            <img src="{{ asset('src/logors.png') }}"
                 alt="Logo RS"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
            <span class="logo-fallback" style="display:none">🏥</span>
        </div>

        <div class="rs-header-info">
            <div class="rs-name">{{ $setting?->nama_instansi ?? 'Nama Rumah Sakit' }}</div>
            <div class="rs-address">
                📍 {{ $setting?->alamat_instansi ?? '' }}
                @if($setting?->kabupaten), {{ $setting->kabupaten }}@endif
                @if($setting?->propinsi), {{ $setting->propinsi }}@endif
            </div>
        </div>

        <div class="rs-datetime">
            <div class="time">
                <span class="live-dot"></span>
                <span id="hdr-clock">{{ now()->format('H:i:s') }}</span>
            </div>
            <div class="date">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
        </div>
    </div>

    <div class="glow-divider"></div>

    {{-- ── Toolbar ── --}}
    <div class="toolbar">
        <div class="toolbar-title">
            📋 Jadwal Praktek Hari Ini
            <span class="hari-badge">{{ $namaHari }}</span>
        </div>
        <form method="GET" action="{{ route('anjungan.poli.jadwal') }}" class="per-page-wrap">
            <span>Tampil:</span>
            <select name="per_page" onchange="this.form.submit()">
                @foreach ([10, 20, 50] as $n)
                    <option value="{{ $n }}" {{ $perPage == $n ? 'selected' : '' }}>{{ $n }} / hal</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- ── Tabel ── --}}
    <div class="table-wrap">
        <table class="jadwal-table">
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="26%">Nama Dokter</th>
                    <th width="24%">Poliklinik</th>
                    <th width="11%">Jam Mulai</th>
                    <th width="11%">Jam Selesai</th>
                    <th width="7%">Register</th>
                    <th width="17%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwalList as $i => $item)
                    <tr>
                        <td>{{ $jadwalList->firstItem() + $loop->index }}</td>
                        <td class="td-dokter">{{ $item->nm_dokter }}</td>
                        <td class="td-poli">{{ $item->nm_poli }}</td>
                        <td class="td-jam mulai">{{ substr($item->jam_mulai, 0, 5) }}</td>
                        <td class="td-jam selesai">{{ substr($item->jam_selesai ?? '', 0, 5) }}</td>
                        <td class="td-register">
                            <span class="register-badge">{{ $item->jumlah_register }}</span>
                        </td>
                        <td class="td-aksi">
                            <a href="{{ route('anjungan.poli.display', [
                                        'poli'   => encrypt($item->kd_poli),
                                        'dokter' => encrypt($item->kd_dokter)
                                    ]) }}"
                               target="_blank"
                               class="btn-inline btn-inline-display">
                                <span class="btn-icon">🖥️</span> Display
                            </a>
                            {{-- <a href="{{ route('anjungan.poli.pemanggil', [
                                        'poli'   => encrypt($item->kd_poli),
                                        'dokter' => encrypt($item->kd_dokter)
                                    ]) }}"
                               target="_blank"
                               class="btn-inline btn-inline-pemanggil">
                                <span class="btn-icon">📢</span> Panggil
                            </a> --}}
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="7">
                            <span class="empty-icon-lg">📅</span>
                            <span class="empty-text">
                                Tidak ada jadwal praktek untuk hari ini ({{ $namaHari }})
                            </span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($jadwalList->hasPages())
        <div class="pager-bar">
            <span class="pager-info">
                Menampilkan <strong>{{ $jadwalList->firstItem() }}</strong>–<strong>{{ $jadwalList->lastItem() }}</strong>
                dari <strong>{{ $jadwalList->total() }}</strong> jadwal
            </span>
            <div style="display:flex; align-items:center;">
                {{ $jadwalList->appends(['per_page' => $perPage])->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @else
        <div class="pager-bar">
            <span class="pager-info">
                Total <strong>{{ $jadwalList->total() }}</strong> jadwal hari ini
            </span>
        </div>
        @endif
    </div>

</div>

{{-- ── Footer banner ── --}}
{{-- <div class="footer-fixed" id="footer-area">
    <img src="{{ asset('images/footbanner.png') }}"
         alt="Footer"
         onerror="handleFooterErr(this)">
</div> --}}

@endsection

@push('scripts')
<script>
// ── Clock (HH:MM:SS) ───────────────────────────────────────
(function tickClock() {
    const el = document.getElementById('hdr-clock');
    if (el) {
        el.textContent = new Date().toLocaleTimeString('id-ID', {
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
    }
    setTimeout(tickClock, 1000);
})();

// ── Footer fallback ────────────────────────────────────────
function handleFooterErr(img) {
    img.style.display = 'none';
    document.getElementById('footer-area').innerHTML = `
        <div class="footer-fallback">
            <div class="footer-dot"></div>
            <span>Sistem Informasi Manajemen Rumah Sakit</span>
            <div class="footer-dot"></div>
        </div>`;
}
</script>
@endpush