{{-- resources/views/anjungan/layanan/apotek.blade.php --}}
@extends('layouts.app')

@section('title', 'Apotek & Farmasi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/partials/global.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #03c019 0%, #012c06 100%);
            min-height: 100vh;
            overflow: hidden;
        }

        .centered-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .page-title {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
        }

        .page-title h2 {
            font-size: 38px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            margin: 0;
        }

        .loket-grid {
            width: 380px;  {{-- single card, lebar fixed --}}
        }

        @media (max-width: 768px) {
            .loket-grid { width: 100%; max-width: 360px; }
            .page-title h2 { font-size: 28px; }
        }
    </style>
@endpush

@section('content')
    <div class="centered-wrapper">
        <div class="page-title">
            <h2><i class="fas fa-pills"></i> Apotek & Farmasi</h2>
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