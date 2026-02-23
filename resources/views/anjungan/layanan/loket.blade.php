{{-- resources/views/anjungan/layanan/loket.blade.php --}}
@extends('layouts.app')

@section('title', 'Loket Pendaftaran')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/partials/global.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #012c06 0%, #00250b 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        .main-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .page-title {
            text-align: center;
            color: #fff;
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
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }
        @media (max-width: 768px) {
            .loket-grid { grid-template-columns: 1fr; gap: 25px; }
            .page-title h2 { font-size: 28px; }
        }
    </style>
@endpush

@section('content')
    <div class="main-container">
        <div class="page-title">
            <h2><i class="fas fa-door-open"></i> Loket Pendaftaran</h2>
        </div>

        <div class="loket-grid">
            @foreach(['Loket', 'LoketVIP'] as $type)
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

    <x-anjungan.loading-overlay />

    <x-anjungan.print-area 
        :company="$nama_instansi" 
        :address="$alamat ?? ''"
        :phone="$nomor_telepon ?? ''"
    />
@endsection

@push('scripts')
    @include('anjungan.layanan.antrian-scripts', ['defaultTitle' => 'Loket Pendaftaran'])
@endpush