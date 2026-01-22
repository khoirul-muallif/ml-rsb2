@extends('layouts.app')
@section('title', 'Anjungan Mandiri')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/partials/global.css') }}">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .main-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .loket-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; }
        .welcome { text-align: center; color: #fff; margin-bottom: 40px; }
    </style>
@endpush

@section('content')
<x-header :logo="$logo ?? null" :title="$nama_instansi" :subtitle="$alamat ?? ''" :showTime="false" />

<div class="main-container">
    <div class="welcome">
        <h2>Selamat Datang di Anjungan Mandiri</h2>
        <p>Silakan pilih layanan yang Anda butuhkan</p>
    </div>

    <div class="loket-grid">
        @foreach($loket_types as $config)
            <div onclick="ambilAntrian('{{ $config['type'] }}')">
                <x-loket-card :config="$config" :type="$config['type']" :stats="$stats[$config['type']] ?? []" />
            </div>
        @endforeach
    </div>
</div>

<x-running-text :text="$running_text" speed="30" />
<x-footer :company="$nama_instansi" powered="mLITE" />
<x-loading-overlay />
<x-print-area :logo="$logo ?? null" :company="$nama_instansi" :address="$alamat ?? ''" />

@push('scripts')
    <script src="{{ asset('js/partials/utils.js') }}"></script>
    <script>
    function ambilAntrian(type) {
        showLoading();
        fetch('/anjungan/loket/ambil', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ type })
        }).then(r => r.json()).then(data => {
            document.getElementById('printNumber').textContent = data.display;
            document.getElementById('printLabel').textContent = data.label;
            hideLoading();
            window.print();
        });
    }
    </script>
@endpush

@endsection