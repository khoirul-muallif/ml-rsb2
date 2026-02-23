{{-- resources/views/anjungan/poli/url-generator.blade.php --}}
@extends('layouts.appb')

@section('title', 'Generator URL Antrian Poli')

@push('styles')
<style>
    body { background: var(--navy-950); }

    .gen-root {
        max-width: 680px; margin: 0 auto;
        padding: 32px 16px;
    }

    /* ── Page header ────────────────────────────────────── */
    .page-header { margin-bottom: 28px; }
    .page-header h1 {
        font-size: 1.5rem; font-weight: 800;
        color: var(--white); line-height: 1.2;
    }
    .page-header p {
        font-size: .875rem; color: var(--slate-400);
        margin-top: 6px;
    }
    .page-header p strong { color: var(--teal-400); }

    /* ── Form card ──────────────────────────────────────── */
    .form-card {
        background: var(--navy-900);
        border: 1px solid var(--navy-700);
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 20px;
    }
    .form-group { margin-bottom: 18px; }
    .form-group:last-child { margin-bottom: 0; }

    .form-label {
        display: block; margin-bottom: 6px;
        font-size: .78rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .07em;
        color: var(--slate-400);
    }
    .form-select-rs {
        width: 100%;
        background: var(--navy-800);
        border: 1px solid var(--navy-600);
        color: var(--white);
        padding: 10px 14px; border-radius: 8px;
        font-size: .9rem; font-family: var(--font-ui);
        outline: none; cursor: pointer;
        transition: border-color .15s, box-shadow .15s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%2394a3b8' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 12px;
        padding-right: 36px;
    }
    .form-select-rs:focus {
        border-color: var(--teal-500);
        box-shadow: 0 0 0 3px rgba(45,212,191,.12);
    }
    .form-select-rs option { background: var(--navy-800); }

    .btn-generate {
        width: 100%;
        background: var(--teal-500);
        border: none; color: var(--navy-950);
        padding: 12px 24px; border-radius: 8px;
        font-size: .9rem; font-weight: 800;
        font-family: var(--font-ui);
        cursor: pointer; letter-spacing: .02em;
        transition: background .15s, transform .1s, box-shadow .15s;
        margin-top: 4px;
    }
    .btn-generate:hover:not(:disabled) {
        background: var(--teal-400);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(45,212,191,.3);
    }
    .btn-generate:disabled {
        background: var(--navy-600);
        color: var(--slate-400); cursor: not-allowed; transform: none;
    }

    /* ── Result cards ───────────────────────────────────── */
    .result-section { display: none; }
    .result-section.visible { display: block; }

    .result-card {
        background: var(--navy-900);
        border: 1px solid var(--navy-700);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 12px;
    }
    .result-card:last-child { margin-bottom: 0; }

    .result-card-header {
        padding: 10px 16px;
        background: var(--navy-800);
        border-bottom: 1px solid var(--navy-700);
        display: flex; align-items: center; gap: 8px;
        font-size: .75rem; font-weight: 700;
        letter-spacing: .07em; text-transform: uppercase;
    }
    .result-card-header .dot {
        width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
    }

    .result-card-body { padding: 14px 16px; }

    .url-input-wrap {
        display: flex; gap: 8px; margin-bottom: 10px;
    }
    .url-input {
        flex: 1;
        background: var(--navy-950);
        border: 1px solid var(--navy-600);
        color: var(--slate-300);
        padding: 8px 12px; border-radius: 7px;
        font-size: .78rem; font-family: var(--font-mono);
        outline: none; min-width: 0;
    }
    .btn-copy {
        background: var(--navy-700);
        border: 1px solid var(--navy-600);
        color: var(--slate-300);
        padding: 8px 14px; border-radius: 7px;
        font-size: .78rem; font-weight: 600;
        font-family: var(--font-ui); cursor: pointer;
        white-space: nowrap; flex-shrink: 0;
        transition: background .1s, border-color .1s, color .1s;
    }
    .btn-copy:hover {
        background: var(--navy-600);
        border-color: var(--teal-500); color: var(--teal-400);
    }
    .btn-copy.copied {
        background: rgba(45,212,191,.1);
        border-color: var(--teal-500); color: var(--teal-400);
    }

    .btn-buka {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 16px; border-radius: 7px;
        font-size: .8rem; font-weight: 700;
        text-decoration: none; cursor: pointer;
        transition: opacity .15s, transform .1s;
    }
    .btn-buka:hover { opacity: .85; transform: translateY(-1px); }

    .btn-buka-display   { background: rgba(96,165,250,.15); border: 1px solid rgba(96,165,250,.3); color: #93c5fd; }
    .btn-buka-pemanggil { background: rgba(244,63,94,.12);  border: 1px solid rgba(244,63,94,.3);  color: var(--rose-400); }
    .btn-buka-jadwal    { background: rgba(45,212,191,.1);  border: 1px solid rgba(45,212,191,.25); color: var(--teal-400); }

    /* ── Divider ────────────────────────────────────────── */
    .divider {
        border: none; border-top: 1px solid var(--navy-700);
        margin: 20px 0;
    }

    /* ── Jadwal shortcut ────────────────────────────────── */
    .jadwal-card {
        background: var(--navy-900);
        border: 1px solid var(--navy-700);
        border-left: 3px solid var(--teal-400);
        border-radius: 10px;
        padding: 14px 16px;
        display: flex; align-items: center;
        justify-content: space-between; gap: 12px; flex-wrap: wrap;
    }
    .jadwal-card .info .title {
        font-weight: 700; color: var(--white); font-size: .9rem;
    }
    .jadwal-card .info .sub {
        font-size: .78rem; color: var(--slate-400); margin-top: 2px;
    }

    /* ── Error state ────────────────────────────────────── */
    .error-msg {
        background: rgba(244,63,94,.08);
        border: 1px solid rgba(244,63,94,.25);
        color: var(--rose-400);
        padding: 10px 14px; border-radius: 8px;
        font-size: .83rem; margin-top: 12px;
        display: none;
    }
    .error-msg.visible { display: block; }
</style>
@endpush

@section('content')

<div class="gen-root">

    <div class="page-header">
        <h1>🔗 Generator URL Antrian Poli</h1>
        <p>Pilih poliklinik dan dokter, lalu klik <strong>Generate</strong>. URL terenkripsi akan muncul untuk Display TV dan Pemanggil.</p>
    </div>

    {{-- ── Form ── --}}
    <div class="form-card">
        <div class="form-group">
            <label class="form-label" for="sel-poli">Poliklinik</label>
            <select class="form-select-rs" id="sel-poli" required>
                <option value="">— Pilih Poliklinik —</option>
                @foreach ($poliList as $p)
                    <option value="{{ $p->kd_poli }}">{{ $p->kd_poli }} — {{ $p->nm_poli }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label" for="sel-dokter">Dokter</label>
            <select class="form-select-rs" id="sel-dokter" required>
                <option value="">— Pilih Dokter —</option>
                @foreach ($dokterList as $d)
                    <option value="{{ $d->kd_dokter }}">{{ $d->nm_dokter }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <button class="btn-generate" id="btn-gen" type="button" onclick="generateUrl()">
                ⚡ Generate URL
            </button>
        </div>
        <div class="error-msg" id="error-msg"></div>
    </div>

    {{-- ── Hasil ── --}}
    <div class="result-section" id="result-section">

        {{-- Display TV --}}
        <div class="result-card">
            <div class="result-card-header">
                <span class="dot" style="background:#93c5fd;box-shadow:0 0 4px #93c5fd55"></span>
                🖥️ Display TV — Layar Ruang Tunggu
            </div>
            <div class="result-card-body">
                <div class="url-input-wrap">
                    <input type="text" id="url-display" class="url-input" readonly placeholder="URL akan muncul di sini">
                    <button class="btn-copy" onclick="copyUrl('url-display', this)">Salin</button>
                </div>
                <a id="link-display" href="#" target="_blank" class="btn-buka btn-buka-display">
                    Buka Display →
                </a>
            </div>
        </div>

        {{-- Pemanggil --}}
        <div class="result-card">
            <div class="result-card-header">
                <span class="dot" style="background:var(--rose-400);box-shadow:0 0 4px rgba(244,63,94,.4)"></span>
                📢 Pemanggil — Komputer / Tablet Petugas
            </div>
            <div class="result-card-body">
                <div class="url-input-wrap">
                    <input type="text" id="url-pemanggil" class="url-input" readonly placeholder="URL akan muncul di sini">
                    <button class="btn-copy" onclick="copyUrl('url-pemanggil', this)">Salin</button>
                </div>
                <a id="link-pemanggil" href="#" target="_blank" class="btn-buka btn-buka-pemanggil">
                    Buka Pemanggil →
                </a>
            </div>
        </div>

    </div>

    <hr class="divider">

    {{-- Jadwal Shortcut --}}
    <div class="jadwal-card">
        <div class="info">
            <div class="title">📋 Jadwal Praktek Dokter</div>
            <div class="sub">Tidak perlu parameter — otomatis menampilkan jadwal hari ini</div>
        </div>
        <a href="{{ route('anjungan.poli.jadwal') }}" target="_blank" class="btn-buka btn-buka-jadwal">
            Buka Jadwal →
        </a>
    </div>

</div>

@endsection

@push('scripts')
<script>
// FIX: Pakai endpoint dedicated, bukan menumpang route display
const API_GEN = "{{ route('anjungan.poli.url.generator') }}";
const CSRF    = document.querySelector('meta[name="csrf-token"]').content;

async function generateUrl() {
    const poli   = document.getElementById('sel-poli').value;
    const dokter = document.getElementById('sel-dokter').value;
    const errEl  = document.getElementById('error-msg');

    errEl.className = 'error-msg';

    if (!poli || !dokter) {
        showError('Pilih poliklinik dan dokter terlebih dahulu.');
        return;
    }

    const btn = document.getElementById('btn-gen');
    btn.disabled = true;
    btn.textContent = '⏳ Generating...';

    try {
        const resp = await fetch(
            `${API_GEN}?poli_raw=${encodeURIComponent(poli)}&dokter_raw=${encodeURIComponent(dokter)}`,
            { headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' } }
        );

        if (!resp.ok) {
            const d = await resp.json();
            throw new Error(d.error ?? 'Gagal generate URL.');
        }

        const data = await resp.json();
        const params  = `poli=${encodeURIComponent(data.enc_poli)}&dokter=${encodeURIComponent(data.enc_dokter)}`;

        const baseDisplay   = "{{ route('anjungan.poli.display') }}";
        const basePemanggil = "{{ route('anjungan.poli.pemanggil') }}";

        const urlDisp = `${baseDisplay}?${params}`;
        const urlPem  = `${basePemanggil}?${params}`;

        document.getElementById('url-display').value   = urlDisp;
        document.getElementById('link-display').href   = urlDisp;
        document.getElementById('url-pemanggil').value = urlPem;
        document.getElementById('link-pemanggil').href = urlPem;

        const result = document.getElementById('result-section');
        result.classList.add('visible');
        result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

    } catch (err) {
        showError(err.message ?? 'Gagal generate URL. Coba lagi.');
    } finally {
        btn.disabled = false;
        btn.textContent = '⚡ Generate URL';
    }
}

function showError(msg) {
    const el = document.getElementById('error-msg');
    el.textContent = '⚠️ ' + msg;
    el.classList.add('visible');
}

async function copyUrl(elId, btn) {
    const val = document.getElementById(elId).value;
    if (!val) return;
    try {
        await navigator.clipboard.writeText(val);
        const orig = btn.textContent;
        btn.textContent = '✓ Disalin';
        btn.classList.add('copied');
        setTimeout(() => {
            btn.textContent = orig;
            btn.classList.remove('copied');
        }, 2000);
    } catch {
        showToast('Gagal menyalin URL.', 'error');
    }
}
</script>
@endpush