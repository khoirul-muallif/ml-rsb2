{{-- resources/views/anjungan/layanan/apotek.blade.php --}}
@extends('layouts.app')

@section('title', 'Apotek & Farmasi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/partials/global.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #03c019 0%, #012c06 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        .main-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .page-title {
            text-align: center;
            color: #fff;
            margin-bottom: 50px;
        }
        .page-title h2 {
            font-size: 38px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .page-title p { font-size: 18px; opacity: 0.95; }
        .loket-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            max-width: 450px;
            margin: 0 auto;
        }
        @media (max-width: 768px) {
            .page-title h2 { font-size: 28px; }
        }
    </style>
@endpush

@section('content')
    <x-anjungan.header 
        :logo="$logo ?? null"
        :title="$nama_instansi"
        :subtitle="$alamat ?? ''"
        :showTime="false"
    />

    <div class="main-container">
        <div class="page-title">
            <h2><i class="fas fa-pills"></i> Apotek & Farmasi</h2>
            <p>Layanan pengambilan obat dan konsultasi farmasi</p>
        </div>

        <div class="loket-grid">
            @foreach(['Apotek'] as $type)
                @php
                    $config = collect($loket_types)->firstWhere('type', $type);
                    $stats  = $summary[$type] ?? ['total' => 0, 'menunggu' => 0, 'last_number' => 0];
                @endphp
                <div onclick="ambilAntrian('{{ $type }}')">
                    <x-anjungan.loket-card 
                        :config="$config"
                        :type="$type"
                        :stats="$stats"
                        :nextNumber="$stats['last_number'] ?? 0"
                    />
                </div>
            @endforeach
        </div>
    </div>

    <x-anjungan.running-text :text="$running_text" speed="30" />
    <x-anjungan.footer :company="$nama_instansi" powered="mLITE" />
    <x-anjungan.loading-overlay />

    <x-anjungan.print-area 
        :company="$nama_instansi" 
        :address="$alamat ?? ''"
        :phone="$nomor_telepon ?? ''"
    />
@endsection

@push('scripts')
    @include('anjungan.layanan.antrian-scripts', ['defaultTitle' => 'Apotek & Farmasi'])
@endpush