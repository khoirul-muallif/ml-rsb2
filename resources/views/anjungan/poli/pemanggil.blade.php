{{-- resources/views/anjungan/poli/pemanggil.blade.php --}}
@extends('layouts.appb')

@section('title', 'Pemanggil — ' . ($jadwal?->poliklinik?->nm_poli ?? $kdPoli))

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
<style>
    /* ── Root Variables ─────────────────────────────────── */
    :root {
        --g950: #052e16;
        --g900: #14532d;
        --g800: #166534;
        --g700: #15803d;
        --g600: #16a34a;
        --g500: #22c55e;
        --g400: #4ade80;
        --g300: #86efac;
        --g200: #bbf7d0;

        --em400: #34d399;
        --em500: #10b981;

        --amber: #f59e0b;
        --amber-lt: #fcd34d;
        --rose:  #f43f5e;
        --rose-lt: #fda4af;
        --blue:  #60a5fa;

        --white: #ffffff;

        --glass:        rgba(5, 46, 22, 0.68);
        --glass-dark:   rgba(5, 46, 22, 0.82);
        --glass-border: rgba(74, 222, 128, 0.18);
        --glass-hover:  rgba(34, 197, 94, 0.12);

        --font-ui:   'Plus Jakarta Sans', sans-serif;
        --font-mono: 'JetBrains Mono', monospace;
    }

    *, *::before, *::after { box-sizing: border-box; }

    body {
        font-family: var(--font-ui);
        background:
            linear-gradient(150deg,
                rgba(5,46,22,0.85) 0%,
                rgba(20,83,45,0.72) 50%,
                rgba(5,46,22,0.90) 100%
            ),
            url("{{ asset('src/bg1.jpeg') }}") center/cover no-repeat fixed;
        min-height: 100vh;
        color: var(--white);
        -webkit-font-smoothing: antialiased;
    }

    /* Subtle grid overlay */
    body::before {
        content: '';
        position: fixed; inset: 0; pointer-events: none; z-index: 0;
        background-image:
            linear-gradient(rgba(74,222,128,.025) 1px, transparent 1px),
            linear-gradient(90deg, rgba(74,222,128,.025) 1px, transparent 1px);
        background-size: 48px 48px;
    }

    /* ── Page layout ──────────────────────────────────── */
    .pemanggil-root {
        max-width: 1100px; margin: 0 auto;
        padding: 16px 16px 36px;
        position: relative; z-index: 1;
    }

    /* ── Shared glass card ────────────────────────────── */
    .glass-card {
        background: var(--glass);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 36px rgba(0,0,0,.35),
                    inset 0 1px 0 rgba(74,222,128,.08);
    }

    /* ── Header Dokter ────────────────────────────────── */
    .dokter-header {
        background: var(--glass-dark);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-left: 3px solid var(--g400);
        border-radius: 16px;
        padding: 16px 22px;
        margin-bottom: 14px;
        display: flex; align-items: center;
        justify-content: space-between; gap: 16px;
        flex-wrap: wrap;
        box-shadow: 0 4px 24px rgba(0,0,0,.3),
                    inset 0 1px 0 rgba(74,222,128,.1);
        animation: fade-up .4s ease both;
    }

    .dh-left .nm-poli {
        font-size: 1.3rem; font-weight: 800;
        color: var(--white);
        text-shadow: 0 2px 8px rgba(0,0,0,.4);
    }
    .dh-left .nm-dokter {
        font-size: .9rem; color: var(--g400);
        font-weight: 600; margin-top: 3px;
    }
    .dh-left .jam-info {
        font-size: .78rem; color: var(--g300);
        margin-top: 5px; display: flex;
        align-items: center; gap: 10px; opacity: .8;
    }

    .dh-right { text-align: right; }
    .dh-right .date-txt {
        font-size: .75rem; color: var(--g300); opacity: .7;
    }

    .badge-live {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 12px; border-radius: 20px;
        font-size: .7rem; font-weight: 800;
        letter-spacing: .08em; margin-top: 5px;
    }
    .badge-live.online {
        background: rgba(74,222,128,.1);
        border: 1px solid rgba(74,222,128,.3);
        color: var(--g400);
    }
    .badge-live.offline {
        background: rgba(244,63,94,.1);
        border: 1px solid rgba(244,63,94,.3);
        color: var(--rose-lt);
    }
    .live-dot-sm {
        width: 6px; height: 6px; border-radius: 50%;
        background: currentColor;
        animation: blink 2s ease-in-out infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.2} }

    /* ── Box Dipanggil ────────────────────────────────── */
    #box-dipanggil {
        background: rgba(244,63,94,.08);
        border: 1px solid rgba(244,63,94,.3);
        border-left: 3px solid var(--rose);
        border-radius: 16px;
        padding: 16px 22px;
        margin-bottom: 14px;
        display: none;
        backdrop-filter: blur(16px);
        box-shadow: 0 0 30px rgba(244,63,94,.1);
        animation: fade-up .3s ease both;
    }
    .dp-label {
        font-size: .7rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .1em;
        color: var(--rose-lt); margin-bottom: 10px;
    }
    .dp-content {
        display: flex; align-items: center;
        justify-content: space-between; gap: 16px; flex-wrap: wrap;
    }
    .dp-nomor {
        font-family: var(--font-mono);
        font-size: 2.2rem; font-weight: 700;
        color: var(--rose-lt); line-height: 1;
        text-shadow: 0 0 20px rgba(244,63,94,.4);
    }
    .dp-nama {
        font-size: 1.05rem; font-weight: 700;
        color: var(--white); margin-top: 5px;
    }
    .btn-ulang-top {
        background: rgba(245,158,11,.12);
        border: 1px solid rgba(245,158,11,.35);
        color: var(--amber-lt);
        padding: 9px 20px; border-radius: 10px;
        font-size: .85rem; font-weight: 700;
        font-family: var(--font-ui);
        cursor: pointer; white-space: nowrap;
        transition: all .15s;
        flex-shrink: 0;
    }
    .btn-ulang-top:hover:not(:disabled) {
        background: rgba(245,158,11,.22);
        border-color: var(--amber-lt);
        box-shadow: 0 0 12px rgba(245,158,11,.2);
    }
    .btn-ulang-top:disabled { opacity: .35; cursor: not-allowed; }
    .dp-hint { font-size: .7rem; color: var(--g300); opacity: .6; margin-top: 4px; }

    /* ── Stat Row ─────────────────────────────────────── */
    .stat-row {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 10px; margin-bottom: 14px;
        animation: fade-up .4s .05s ease both;
    }
    .stat-box {
        background: var(--glass);
        backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        border-radius: 14px;
        padding: 16px 18px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,.2);
        transition: transform .2s;
    }
    .stat-box:hover { transform: translateY(-2px); }
    .stat-box .angka {
        font-family: var(--font-mono);
        font-size: 2.2rem; font-weight: 700;
        line-height: 1; display: block;
    }
    .stat-box .lbl {
        font-size: .68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em;
        display: block; margin-top: 5px; opacity: .7;
    }
    .stat-tunggu   .angka { color: var(--amber-lt); }
    .stat-tunggu   .lbl   { color: var(--amber-lt); }
    .stat-dipanggil .angka { color: var(--rose-lt); }
    .stat-dipanggil .lbl   { color: var(--rose-lt); }
    .stat-total    .angka { color: var(--g400); }
    .stat-total    .lbl   { color: var(--g400); }

    /* ── Card (tabel wrapper) ─────────────────────────── */
    .card-rs {
        margin-bottom: 14px;
        animation: fade-up .4s .1s ease both;
    }
    .card-rs:last-child { margin-bottom: 0; }

    .card-rs-header {
        padding: 12px 18px;
        background: rgba(5,46,22,.6);
        backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-bottom: none;
        border-radius: 16px 16px 0 0;
        display: flex; align-items: center;
        justify-content: space-between;
    }
    .card-rs-body {
        background: var(--glass);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-top: none;
        border-radius: 0 0 16px 16px;
        overflow: hidden;
        box-shadow: 0 6px 28px rgba(0,0,0,.3);
    }

    .card-rs-title {
        display: flex; align-items: center; gap: 8px;
        font-size: .75rem; font-weight: 800;
        letter-spacing: .08em; text-transform: uppercase;
        color: var(--g400);
    }
    .dot-ind {
        width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
    }
    .dot-green { background: var(--g400); box-shadow: 0 0 6px var(--g400); }
    .dot-red   { background: var(--rose); box-shadow: 0 0 6px var(--rose); }

    .per-page-mini {
        display: flex; align-items: center; gap: 6px;
        font-size: .72rem; color: var(--g300); opacity: .7;
    }
    .per-page-mini select {
        background: rgba(5,46,22,.7);
        border: 1px solid rgba(74,222,128,.25);
        color: var(--white);
        padding: 3px 8px; border-radius: 7px;
        font-size: .75rem; font-family: var(--font-ui);
        outline: none; cursor: pointer;
    }

    /* ── Table ────────────────────────────────────────── */
    .rs-table { width: 100%; border-collapse: collapse; }

    .rs-table thead th {
        padding: 11px 14px;
        font-size: .68rem; font-weight: 800;
        letter-spacing: .1em; text-transform: uppercase;
        color: var(--g400);
        border-bottom: 1px solid rgba(74,222,128,.15);
        white-space: nowrap;
        background: rgba(5,46,22,.3);
    }

    .rs-table tbody tr {
        border-bottom: 1px solid rgba(74,222,128,.07);
        transition: background .12s;
    }
    .rs-table tbody tr:last-child { border-bottom: none; }
    .rs-table tbody tr:hover { background: var(--glass-hover); }

    /* Row states */
    .rs-table tbody tr.row-aktif {
        background: rgba(244,63,94,.07) !important;
    }
    .rs-table tbody tr.row-aktif:hover {
        background: rgba(244,63,94,.12) !important;
    }
    .rs-table tbody tr.row-menunggu {
        background: rgba(245,158,11,.05) !important;
    }

    /* Alternating */
    .rs-table tbody tr:nth-child(even) {
        background: rgba(34,197,94,.04);
    }
    .rs-table tbody tr:nth-child(even):hover { background: var(--glass-hover); }

    .rs-table tbody td {
        padding: 11px 14px;
        font-size: .875rem; vertical-align: middle;
        color: rgba(255,255,255,.85);
    }

    .td-no { text-align: center; color: var(--g400); opacity: .6; font-size: .78rem; font-family: var(--font-mono); }
    .td-no-antrian { font-family: var(--font-mono); font-weight: 700; color: var(--amber-lt); font-size: .88rem; }
    .td-nama { font-weight: 600; color: var(--white); }
    .td-aksi-col { text-align: center; }

    /* ── Status badges ────────────────────────────────── */
    .bs {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 20px;
        font-size: .7rem; font-weight: 700; letter-spacing: .03em;
    }
    .bs-belum     { background: rgba(245,158,11,.12); border: 1px solid rgba(245,158,11,.3);  color: var(--amber-lt); }
    .bs-terkirim  { background: rgba(96,165,250,.1);  border: 1px solid rgba(96,165,250,.25); color: #93c5fd; }
    .bs-diterima  { background: rgba(74,222,128,.1);  border: 1px solid rgba(74,222,128,.25); color: var(--g400); }
    .bs-dipanggil { background: rgba(244,63,94,.12);  border: 1px solid rgba(244,63,94,.3);   color: var(--rose-lt); }
    .bs-menunggu  { background: rgba(245,158,11,.1);  border: 1px solid rgba(245,158,11,.2);  color: var(--amber-lt); }
    .bs-masuk     { background: rgba(74,222,128,.1);  border: 1px solid rgba(74,222,128,.2);  color: var(--g400); }

    /* ── Buttons ──────────────────────────────────────── */
    .btn-panggil {
        background: linear-gradient(135deg, var(--g600), var(--g700));
        border: 1px solid rgba(74,222,128,.35);
        color: var(--white);
        font-weight: 700; padding: 6px 16px;
        border-radius: 9px; font-size: .78rem;
        font-family: var(--font-ui); cursor: pointer;
        white-space: nowrap;
        box-shadow: 0 2px 10px rgba(34,197,94,.2);
        transition: all .15s;
    }
    .btn-panggil:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--g500), var(--g600));
        box-shadow: 0 0 16px rgba(34,197,94,.35);
        transform: translateY(-1px);
    }
    .btn-panggil:disabled {
        background: rgba(255,255,255,.06);
        border-color: rgba(255,255,255,.1);
        color: rgba(255,255,255,.3);
        cursor: not-allowed; transform: none;
        box-shadow: none;
    }

    .btn-ulang {
        background: rgba(245,158,11,.1);
        border: 1px solid rgba(245,158,11,.3);
        color: var(--amber-lt);
        font-weight: 700; padding: 5px 13px;
        border-radius: 9px; font-size: .78rem;
        font-family: var(--font-ui); cursor: pointer;
        white-space: nowrap;
        transition: all .15s;
    }
    .btn-ulang:hover:not(:disabled) {
        background: rgba(245,158,11,.2);
        border-color: var(--amber-lt);
        box-shadow: 0 0 10px rgba(245,158,11,.2);
    }
    .btn-ulang:disabled { opacity: .3; cursor: not-allowed; }

    /* ── Pagination ───────────────────────────────────── */
    .pager-wrap {
        display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap;
        gap: 8px; padding: 12px 16px;
        background: rgba(5,46,22,.4);
        border-top: 1px solid rgba(74,222,128,.12);
    }
    .pager-info { font-size: .78rem; color: var(--g300); opacity: .8; }
    .pager-info strong { color: var(--white); opacity: 1; }
    .pager-btns { display: flex; gap: 4px; flex-wrap: wrap; }
    .pager-btn {
        background: rgba(5,46,22,.6);
        border: 1px solid rgba(74,222,128,.2);
        color: var(--g300);
        padding: 5px 11px; border-radius: 8px;
        font-size: .78rem; cursor: pointer;
        font-family: var(--font-ui);
        transition: all .12s;
        min-width: 34px; text-align: center;
    }
    .pager-btn:hover:not(:disabled) {
        background: rgba(34,197,94,.15);
        border-color: rgba(74,222,128,.4);
        color: var(--white);
    }
    .pager-btn.active {
        background: linear-gradient(135deg, var(--g600), var(--g700));
        border-color: var(--g500);
        color: var(--white); font-weight: 700;
        box-shadow: 0 0 10px rgba(34,197,94,.3);
    }
    .pager-btn:disabled { opacity: .25; cursor: not-allowed; }

    /* ── Empty state ──────────────────────────────────── */
    .empty-cell {
        padding: 36px 20px !important;
        text-align: center;
        color: var(--g300) !important;
    }
    .empty-cell .ei {
        font-size: 2.2rem; display: block;
        margin-bottom: 10px; opacity: .25;
    }
    .empty-cell span:last-child { opacity: .6; font-size: .88rem; }

    /* ── Toast ────────────────────────────────────────── */
    #toast-container {
        position: fixed; top: 20px; right: 20px;
        z-index: 9999; display: flex;
        flex-direction: column; gap: 8px;
    }
    .toast-item {
        background: var(--glass-dark);
        backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 12px 20px;
        font-size: .85rem; font-weight: 600;
        color: var(--white);
        box-shadow: 0 4px 20px rgba(0,0,0,.4);
        animation: slide-in .25s ease;
        max-width: 320px;
    }
    .toast-item.success { border-left: 3px solid var(--g400); }
    .toast-item.error   { border-left: 3px solid var(--rose); }
    .toast-item.warning { border-left: 3px solid var(--amber-lt); }
    @keyframes slide-in {
        from { opacity:0; transform: translateX(16px); }
        to   { opacity:1; transform: translateX(0); }
    }

    /* ── Animations ───────────────────────────────────── */
    @keyframes fade-up {
        from { opacity:0; transform:translateY(12px); }
        to   { opacity:1; transform:translateY(0); }
    }

    /* ── Responsive ───────────────────────────────────── */
    @media (max-width: 640px) {
        .stat-row { grid-template-columns: repeat(3,1fr); }
        .stat-box .angka { font-size: 1.6rem; }
        .dp-content { flex-direction: column; }
    }
</style>
@endpush

@section('content')

<div id="toast-container"></div>

<div class="pemanggil-root">

    {{-- ── Header Dokter ── --}}
    <div class="dokter-header">
        <div class="dh-left">
            <div class="nm-poli">{{ $jadwal?->poliklinik?->nm_poli ?? $kdPoli }}</div>
            <div class="nm-dokter">{{ $jadwal?->dokter?->nm_dokter ?? $kdDokter }}</div>
            <div class="jam-info">
                <span>🕐 {{ $jadwal?->jam_praktek ?? '—' }}</span>
                <span id="hdr-jam">{{ now()->format('H:i') }}</span>
            </div>
        </div>
        <div class="dh-right">
            <div class="date-txt">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
            <div id="badge-live" class="badge-live online">
                <span class="live-dot-sm"></span> LIVE
            </div>
        </div>
    </div>

    {{-- ── Box Sedang Dipanggil ── --}}
    <div id="box-dipanggil">
        <div class="dp-label">🔔 Sedang Dipanggil Sekarang</div>
        <div class="dp-content">
            <div>
                <div class="dp-nomor" id="dp-nomor">—</div>
                <div class="dp-nama"  id="dp-nama">—</div>
            </div>
            <div class="text-end">
                <button class="btn-ulang-top" id="btn-ulang-top"
                        data-no-rawat="" disabled>
                    🔁 Panggil Ulang
                </button>
                <div class="dp-hint">pasien tidak sadar / tidak maju</div>
            </div>
        </div>
    </div>

    {{-- ── Statistik ── --}}
    <div class="stat-row">
        <div class="stat-box stat-tunggu">
            <span class="angka" id="st-tunggu">0</span>
            <span class="lbl">Menunggu</span>
        </div>
        <div class="stat-box stat-dipanggil">
            <span class="angka" id="st-dipanggil">0</span>
            <span class="lbl">Dipanggil</span>
        </div>
        <div class="stat-box stat-total">
            <span class="angka" id="st-total">0</span>
            <span class="lbl">Sudah Masuk</span>
        </div>
    </div>

    {{-- ── Tabel 1: Menunggu ── --}}
    <div class="card-rs">
        <div class="card-rs-header">
            <div class="card-rs-title">
                <span class="dot-ind dot-green"></span>
                Menunggu Dipanggil
            </div>
            <div class="per-page-mini">
                Tampil:
                <select id="per-page-tunggu" onchange="perPageTunggu=+this.value; halamanTunggu=1; renderTunggu()">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
        <div class="card-rs-body">
            <table class="rs-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="16%">No. Antrian</th>
                        <th width="46%">Nama Pasien</th>
                        <th width="18%">Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody-tunggu">
                    <tr><td colspan="5" class="empty-cell">
                        <span class="ei">⏳</span>
                        <span>Memuat data...</span>
                    </td></tr>
                </tbody>
            </table>
            <div id="pager-tunggu" class="pager-wrap" style="display:none"></div>
        </div>
    </div>

    {{-- ── Tabel 2: Sudah Dipanggil ── --}}
    <div class="card-rs">
        <div class="card-rs-header">
            <div class="card-rs-title">
                <span class="dot-ind dot-red"></span>
                Sudah Dipanggil
            </div>
            <div class="per-page-mini">
                Tampil:
                <select id="per-page-dipanggil" onchange="perPageDipanggil=+this.value; halamanDipanggil=1; renderDipanggil()">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
        <div class="card-rs-body">
            <table class="rs-table">
                <thead>
                    <tr>
                        <th width="16%">No. Antrian</th>
                        <th width="48%">Nama Pasien</th>
                        <th width="20%">Status</th>
                        <th width="16%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody-dipanggil">
                    <tr><td colspan="4" class="empty-cell">
                        <span class="ei">📢</span>
                        <span>Belum ada pasien dipanggil</span>
                    </td></tr>
                </tbody>
            </table>
            <div id="pager-dipanggil" class="pager-wrap" style="display:none"></div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
// ── Konstanta ──────────────────────────────────────────────
const ENC_POLI    = @json($encPoli);
const ENC_DOKTER  = @json($encDokter);
const CSRF        = document.querySelector('meta[name="csrf-token"]').content;
const API_DAFTAR  = "{{ route('anjungan.poli.api.pemanggil.daftar') }}";
const API_PANGGIL = "{{ route('anjungan.poli.api.pemanggil.panggil') }}";
const API_ULANG   = "{{ route('anjungan.poli.api.pemanggil.ulang') }}";

// ── State ──────────────────────────────────────────────────
let busy              = false;
let allTunggu         = [];
let halamanTunggu     = 1;
let perPageTunggu     = 10;
let allDipanggil      = [];   // ← simpan semua data dipanggil
let halamanDipanggil  = 1;
let perPageDipanggil  = 10;

// ── Clock ──────────────────────────────────────────────────
setInterval(() => {
    const el = document.getElementById('hdr-jam');
    if (el) el.textContent = new Date().toLocaleTimeString('id-ID',
        { hour: '2-digit', minute: '2-digit' });
}, 1000);

// ── Fetch ──────────────────────────────────────────────────
async function fetchDaftar() {
    try {
        const r = await fetch(
            `${API_DAFTAR}?poli=${encodeURIComponent(ENC_POLI)}&dokter=${encodeURIComponent(ENC_DOKTER)}`
        );
        if (!r.ok) throw new Error();
        const d = await r.json();

        allTunggu = d.menunggu ?? [];

        renderBoxAktif(d.info_dipanggil);
        renderTunggu();
        allDipanggil = d.masuk ?? [];
        renderDipanggil(d.sedang_dipanggil);

        document.getElementById('st-tunggu').textContent    = d.total_menunggu ?? 0;
        document.getElementById('st-dipanggil').textContent =
            d.masuk?.filter(p => p.status_tampil === 'dipanggil').length ?? 0;
        document.getElementById('st-total').textContent     = d.total_masuk ?? 0;

        setLive(true);
    } catch {
        setLive(false);
    }
}

// ── Box Aktif ──────────────────────────────────────────────
function renderBoxAktif(info) {
    const box = document.getElementById('box-dipanggil');
    const btn = document.getElementById('btn-ulang-top');
    if (!info) {
        box.style.display = 'none';
        btn.disabled = true;
        btn.dataset.noRawat = '';
        return;
    }
    document.getElementById('dp-nomor').textContent = info.no_antrian;
    document.getElementById('dp-nama').textContent  = info.nm_pasien;
    btn.dataset.noRawat = info.no_rawat;
    btn.disabled = false;
    box.style.display = 'block';
}

// ── Tabel Tunggu (pagination client-side) ─────────────────
function renderTunggu() {
    const tb    = document.getElementById('tbody-tunggu');
    const pager = document.getElementById('pager-tunggu');

    if (!allTunggu.length) {
        tb.innerHTML = `<tr><td colspan="5" class="empty-cell">
            <span class="ei">✅</span><span>Tidak ada pasien menunggu</span>
        </td></tr>`;
        pager.style.display = 'none';
        return;
    }

    const total  = allTunggu.length;
    const pages  = Math.ceil(total / perPageTunggu);
    halamanTunggu = Math.min(halamanTunggu, pages);
    const start  = (halamanTunggu - 1) * perPageTunggu;
    const slice  = allTunggu.slice(start, start + perPageTunggu);

    const badgeMap = {
        'Belum'          : '<span class="bs bs-belum">Belum</span>',
        'Berkas Terkirim': '<span class="bs bs-terkirim">Terkirim</span>',
        'Berkas Diterima': '<span class="bs bs-diterima">Diterima</span>',
    };

    tb.innerHTML = slice.map((p, i) => `
        <tr>
            <td class="td-no">${start + i + 1}</td>
            <td class="td-no-antrian">${h(p.no_antrian)}</td>
            <td class="td-nama">${h(p.nm_pasien)}</td>
            <td>${badgeMap[p.stts] ?? h(p.stts)}</td>
            <td class="td-aksi-col">
                <button class="btn-panggil" data-no-rawat="${h(p.no_rawat)}">
                    📢 Panggil
                </button>
            </td>
        </tr>`).join('');

    if (pages <= 1) { pager.style.display = 'none'; return; }
    pager.style.display = '';

    const range = buildPageRange(halamanTunggu, pages);
    let btns = `<button class="pager-btn" ${halamanTunggu===1?'disabled':''} onclick="goPage(${halamanTunggu-1})">‹</button>`;
    range.forEach(pg => {
        btns += pg === '...'
            ? `<button class="pager-btn" disabled>…</button>`
            : `<button class="pager-btn ${pg===halamanTunggu?'active':''}" onclick="goPage(${pg})">${pg}</button>`;
    });
    btns += `<button class="pager-btn" ${halamanTunggu===pages?'disabled':''} onclick="goPage(${halamanTunggu+1})">›</button>`;

    pager.innerHTML = `
        <span class="pager-info">
            Tampil <strong>${start+1}</strong>–<strong>${Math.min(start+perPageTunggu,total)}</strong>
            dari <strong>${total}</strong> pasien
        </span>
        <div class="pager-btns">${btns}</div>`;
}

function goPage(pg) {
    const pages = Math.ceil(allTunggu.length / perPageTunggu);
    if (pg < 1 || pg > pages) return;
    halamanTunggu = pg;
    renderTunggu();
}

function buildPageRange(cur, total) {
    if (total <= 7) return Array.from({length: total}, (_, i) => i + 1);
    const r = [];
    if (cur <= 4) {
        for (let i=1; i<=5; i++) r.push(i);
        r.push('...'); r.push(total);
    } else if (cur >= total - 3) {
        r.push(1); r.push('...');
        for (let i=total-4; i<=total; i++) r.push(i);
    } else {
        r.push(1); r.push('...');
        for (let i=cur-1; i<=cur+1; i++) r.push(i);
        r.push('...'); r.push(total);
    }
    return r;
}

// ── Tabel Dipanggil (dengan pagination client-side) ────────
function renderDipanggil(sedangRawat) {
    const tb    = document.getElementById('tbody-dipanggil');
    const pager = document.getElementById('pager-dipanggil');

    if (!allDipanggil.length) {
        tb.innerHTML = `<tr><td colspan="4" class="empty-cell">
            <span class="ei">📢</span><span>Belum ada pasien dipanggil</span>
        </td></tr>`;
        pager.style.display = 'none';
        return;
    }

    const total  = allDipanggil.length;
    const pages  = Math.ceil(total / perPageDipanggil);
    halamanDipanggil = Math.min(halamanDipanggil, pages);
    const start  = (halamanDipanggil - 1) * perPageDipanggil;
    const slice  = allDipanggil.slice(start, start + perPageDipanggil);

    const bMap = {
        dipanggil      : '<span class="bs bs-dipanggil">🔔 Dipanggil</span>',
        menunggu_masuk : '<span class="bs bs-menunggu">⏳ Menunggu</span>',
        sudah_masuk    : '<span class="bs bs-masuk">✅ Masuk</span>',
    };
    const rClass = {
        dipanggil     : 'row-aktif',
        menunggu_masuk: 'row-menunggu',
        sudah_masuk   : '',
    };

    tb.innerHTML = slice.map(p => {
        const st = p.status_tampil ?? (p.no_rawat === sedangRawat ? 'dipanggil' : 'menunggu_masuk');
        return `
        <tr class="${rClass[st] ?? ''}">
            <td class="td-no-antrian">${h(p.no_antrian)}</td>
            <td class="td-nama">${h(p.nm_pasien)}</td>
            <td>${bMap[st] ?? h(p.stts)}</td>
            <td class="td-aksi-col">
                <button class="btn-ulang" data-no-rawat="${h(p.no_rawat)}">🔁 Ulang</button>
            </td>
        </tr>`;
    }).join('');

    if (pages <= 1) { pager.style.display = 'none'; return; }
    pager.style.display = '';

    const range = buildPageRange(halamanDipanggil, pages);
    let btns = `<button class="pager-btn" ${halamanDipanggil===1?'disabled':''} onclick="goPageDipanggil(${halamanDipanggil-1})">‹</button>`;
    range.forEach(pg => {
        btns += pg === '...'
            ? `<button class="pager-btn" disabled>…</button>`
            : `<button class="pager-btn ${pg===halamanDipanggil?'active':''}" onclick="goPageDipanggil(${pg})">${pg}</button>`;
    });
    btns += `<button class="pager-btn" ${halamanDipanggil===pages?'disabled':''} onclick="goPageDipanggil(${halamanDipanggil+1})">›</button>`;

    pager.innerHTML = `
        <span class="pager-info">
            Tampil <strong>${start+1}</strong>–<strong>${Math.min(start+perPageDipanggil,total)}</strong>
            dari <strong>${total}</strong> pasien
        </span>
        <div class="pager-btns">${btns}</div>`;
}

function goPageDipanggil(pg) {
    const pages = Math.ceil(allDipanggil.length / perPageDipanggil);
    if (pg < 1 || pg > pages) return;
    halamanDipanggil = pg;
    renderDipanggil();
}

// ── Event Delegation ───────────────────────────────────────
document.addEventListener('click', (e) => {
    if (busy) return;

    const btnPanggil = e.target.closest('.btn-panggil');
    if (btnPanggil) { doPanggil(btnPanggil.dataset.noRawat, btnPanggil); return; }

    const btnUlang = e.target.closest('.btn-ulang');
    if (btnUlang) { doUlang(btnUlang.dataset.noRawat, btnUlang); return; }

    const btnUlangTop = e.target.closest('#btn-ulang-top');
    if (btnUlangTop && !btnUlangTop.disabled) {
        doUlang(btnUlangTop.dataset.noRawat, btnUlangTop);
    }
});

// ── API Actions ────────────────────────────────────────────
async function doPanggil(noRawat, btn) {
    await kirim(API_PANGGIL, noRawat, btn);
}
async function doUlang(noRawat, btn) {
    const target = noRawat || document.getElementById('btn-ulang-top').dataset.noRawat;
    if (!target) return;
    await kirim(API_ULANG, target, btn);
}

async function kirim(url, noRawat, btn) {
    busy = true;
    setAllDisabled(true);
    const origTxt = btn?.innerHTML ?? '';
    if (btn) btn.innerHTML = '<span style="opacity:.6">⏳</span>';

    try {
        const r = await fetch(url, {
            method : 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body   : JSON.stringify({ poli: ENC_POLI, dokter: ENC_DOKTER, no_rawat: noRawat }),
        });
        const d = await r.json();
        const isUlang = url === API_ULANG;
        showToast(
            d.success
                ? (isUlang ? `🔁 Panggil ulang: ${d.no_antrian}` : `✅ ${d.message}`)
                : `❌ ${d.message}`,
            d.success ? (isUlang ? 'warning' : 'success') : 'error'
        );
        await fetchDaftar();
    } catch {
        showToast('❌ Gagal terhubung ke server.', 'error');
        if (btn) btn.innerHTML = origTxt;
    } finally {
        busy = false;
        setAllDisabled(false);
    }
}

// ── Utils ──────────────────────────────────────────────────
function setAllDisabled(s) {
    document.querySelectorAll('.btn-panggil, .btn-ulang, .btn-ulang-top')
        .forEach(b => b.disabled = s);
}

function setLive(ok) {
    const el = document.getElementById('badge-live');
    el.innerHTML = ok
        ? '<span class="live-dot-sm"></span> LIVE'
        : '<span class="live-dot-sm" style="background:var(--rose-lt)"></span> OFFLINE';
    el.className = `badge-live ${ok ? 'online' : 'offline'}`;
}

function showToast(msg, type = 'success') {
    const container = document.getElementById('toast-container');
    const el = document.createElement('div');
    el.className = `toast-item ${type}`;
    el.textContent = msg;
    container.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

function h(s) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(s ?? ''));
    return d.innerHTML;
}

// ── Init ───────────────────────────────────────────────────
window.addEventListener('load', () => {
    fetchDaftar();
    setInterval(fetchDaftar, 10_000);
});
</script>
@endpush