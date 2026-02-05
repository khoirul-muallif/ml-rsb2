{{-- Print Area Component - Centered in Left Column --}}
@props([
    'company' => 'Rumah Sakit',
    'address' => '',
    'phone' => ''
])

<div id="printArea" class="print-area">
    <!-- Konten di kolom kiri saja (50% lebar) -->
    <div class="left-column">
        <div class="print-header">
            {{ $company }}<br>
            @if($address)
                {{ $address }}<br>
            @endif
            @if($phone)
                Telp: {{ $phone }}
            @endif
            <hr>
        </div>
        
        <div class="print-number" id="printNumber">A1</div>
        
        <div class="print-date" id="printDate">[{{ date('Y-m-d') }}]</div>
        
        <div class="print-title" id="printTitle">Loket Pendaftaran</div>
        
       
    </div>
</div>

<style>
.print-area {
    display: none;
}

@media print {
    @page {
        size: A4;
        margin: 0;
    }

    body * { 
        visibility: hidden; 
    }

    .print-area,
    .print-area * { 
        visibility: visible;
    }

    .print-area {
        display: block;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 2mm;
        font-family: Arial, sans-serif;
    }

    /* ✅ Kolom kiri saja - 30% lebar */
    .left-column {
        width: 30%;
        max-width: 90mm;
    }

    /* ✅ Semua text-align: center TAPI di dalam kolom kiri */
    .print-header {
        text-align: center;
        font-size: 10px;
        line-height: 1.5;
        margin-bottom: 10px;
    }

    .print-number {
        text-align: center;
        font-size: 50px;
        font-weight: bold;
        margin: 10px 0;
        letter-spacing: 2px;
    }

    .print-date {
        text-align: center;
        font-size: 14px;
        margin: 10px 0;
    }

    .print-title {
        text-align: center;
        font-size: 14px;
        margin: 15px 0;
    }

    
}
</style>