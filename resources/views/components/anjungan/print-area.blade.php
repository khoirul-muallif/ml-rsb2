{{-- Print Area Component - EPSON TM-T82X Thermal Printer (80mm Roll Paper) --}}
{{-- ⚠️  autoPrint dihapus — print HANYA terjadi saat window.triggerPrint() dipanggil --}}
@props([
    'company' => 'Rumah Sakit',
    'address' => '',
    'phone'   => '',
])

<div id="printArea" class="print-area">
    <div class="print-header">
        <strong>{{ $company }}</strong>
        @if($address)
            <br>{{ $address }}
        @endif
        @if($phone)
            <br>Telp: {{ $phone }}
        @endif
        <div class="divider"></div>
    </div>

    <div class="print-number" id="printNumber">-</div>

    <div class="print-date" id="printDate">{{ date('d/m/Y H:i') }}</div>

    <div class="print-title" id="printTitle">-</div>

    <div class="divider"></div>
    <div class="print-footer">Terima Kasih</div>
</div>

<style>
/* SCREEN: sembunyikan */
.print-area {
    display: none;
}

@media print {
    @page {
        margin: 0mm !important;
    }

    /* Sembunyikan seluruh halaman */
    body > * {
        display: none !important;
    }

    /* Tampilkan hanya #printArea (JS pindahkan ke body langsung) */
    body > #printArea {
        display: block !important;
    }

    body {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
        width: auto !important;
    }

    .print-area {
        display: block !important;
        width: 72mm;
        margin: 0;
        padding: 3mm 4mm;
        font-family: Arial, sans-serif;
        font-size: 10pt;
        color: #000 !important;
        background: #fff !important;
        page-break-after: avoid;
        break-after: avoid;
    }

    .print-header {
        text-align: center;
        font-size: 9pt;
        line-height: 1.6;
        margin-bottom: 3mm;
    }

    .print-header strong {
        font-size: 11pt;
        text-transform: uppercase;
        display: block;
        margin-bottom: 1mm;
    }

    .divider {
        border: none;
        border-top: 1px dashed #000;
        margin: 2.5mm 0;
    }

    .print-number {
        text-align: center;
        font-size: 52pt;
        font-weight: bold;
        line-height: 1.1;
        letter-spacing: 3px;
        margin: 3mm 0;
        color: #000 !important;
    }

    .print-date {
        text-align: center;
        font-size: 9pt;
        margin: 2mm 0;
        color: #000 !important;
    }

    .print-title {
        text-align: center;
        font-size: 12pt;
        font-weight: bold;
        text-transform: uppercase;
        margin: 2mm 0;
        color: #000 !important;
    }

    .print-footer {
        text-align: center;
        font-size: 9pt;
        margin-top: 1mm;
        color: #000 !important;
    }
}
</style>

<script>
(function () {
    'use strict';

    /**
     * Pindahkan #printArea ke langsung di bawah <body> agar
     * selector CSS "body > #printArea" bekerja dalam nested Blade layout.
     * Dipanggil sekali saat DOM siap — TANPA langsung print.
     */
    function movePrintAreaToBody() {
        var el = document.getElementById('printArea');
        if (el && el.parentNode !== document.body) {
            document.body.appendChild(el);
        }
    }

    /**
     * Update konten struk lalu cetak.
     * Dipanggil HANYA dari ambilAntrian() setelah API sukses.
     *
     * Contoh pemakaian di view:
     *   window.triggerPrint({ number: 'A1', title: 'Loket Pendaftaran' })
     */
    window.triggerPrint = function (data) {
        var el = document.getElementById('printArea');
        if (!el) {
            console.error('triggerPrint: #printArea tidak ditemukan');
            return;
        }

        // Update konten
        if (data && data.number) document.getElementById('printNumber').textContent = data.number;
        if (data && data.title)  document.getElementById('printTitle').textContent  = data.title;
        if (data && data.date)   document.getElementById('printDate').textContent   = data.date;

        // Pastikan sudah di body
        movePrintAreaToBody();

        // Cetak dengan jeda kecil agar browser render dulu
        setTimeout(function () {
            window.print();
        }, 200);
    };

    // Hanya pindahkan ke body saat DOM siap — TIDAK print
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', movePrintAreaToBody);
    } else {
        movePrintAreaToBody();
    }

}());
</script>