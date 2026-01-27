{{-- Print Area Component --}}
@props([
    'logo' => null,
    'company' => 'Rumah Sakit',
    'address' => ''
])

<div id="printArea" class="print-area-hidden">
    @if($logo && file_exists(public_path($logo)))
        <img src="{{ asset($logo) }}" alt="Logo" class="print-logo">
    @endif
    
    <h2 style="margin: 20px 0;">{{ $company }}</h2>
    @if($address)
        <p>{{ $address }}</p>
    @endif
    
    <hr>
    
    <div class="print-number" id="printNumber">-</div>
    <div class="print-label" id="printLabel">-</div>
    
    <hr>
    
    <p>{{ date('d-m-Y H:i') }}</p>
    <p style="margin-top: 20px; font-size: 14px;">
        Simpan struk ini sebagai bukti<br>
        Pantau nomor antrian Anda di layar display
    </p>
</div>

<style>
.print-area-hidden {
    display: none;
    text-align: center;
    padding: 40px;
    font-family: monospace;
}

.print-logo {
    width: 80px;
    margin-bottom: 20px;
}

.print-number {
    font-size: 72px;
    font-weight: 700;
    margin: 30px 0;
    letter-spacing: 5px;
}

.print-label {
    font-size: 24px;
    font-weight: 700;
    margin: 20px 0;
}

@media print {
    body * { 
        visibility: hidden; 
    }
    
    .print-area-hidden, .print-area-hidden * { 
        visibility: visible; 
        display: block !important;
    }
    
    .print-area-hidden {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>