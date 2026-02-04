{{-- resources\views\anjungan\layanan\loket.blade.php --}}
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

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.2);
            color: #fff;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid rgba(255,255,255,0.3);
            margin-bottom: 30px;
        }

        .back-button:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
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

        .page-title p {
            font-size: 18px;
            opacity: 0.95;
        }

        .loket-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        @media (max-width: 768px) {
            .loket-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .page-title h2 {
                font-size: 28px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Header -->
    {{-- <x-anjungan.header 
        :logo="$logo ?? null"
        :title="$nama_instansi"
        :subtitle="$alamat ?? ''"
        :showTime="false"
    /> --}}

    <!-- Main Container -->
    <div class="main-container">
        <!-- Back Button -->
        <div class="back-button" onclick="window.location.href='{{ route('anjungan.index') }}'">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Menu Utama</span>
        </div>

        <!-- Page Title -->
        <div class="page-title">
            <h2><i class="fas fa-door-open"></i> Loket Pendaftaran</h2>
            <p>Pilih jenis loket yang Anda butuhkan</p>
        </div>

        <!-- Loket Grid -->
        <div class="loket-grid">
            @foreach(['Loket', 'LoketVIP'] as $type)
                @php
                    $config = collect($loket_types)->firstWhere('type', $type);
                    $stats = $summary[$type] ?? ['total' => 0, 'menunggu' => 0, 'last_number' => 0];
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

    <!-- Running Text -->
    {{-- <x-anjungan.running-text :text="$running_text" speed="30" />

    <!-- Footer -->
    <x-anjungan.footer :company="$nama_instansi" powered="mLITE" /> --}}

    <!-- Loading Overlay -->
    <x-anjungan.loading-overlay />

    <!-- Print Area -->
    <x-anjungan.print-area :logo="$logo ?? null" :company="$nama_instansi" :address="$alamat ?? ''" />

@push('scripts')
    <script src="{{ asset('js/partials/utils.js') }}"></script>
    <script>
    const CSRF_TOKEN = '{{ csrf_token() }}';

    function ambilAntrian(type) {
        showLoading();
        
        fetch('/anjungan/api/ambil', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                document.getElementById('printNumber').textContent = data.display;
                document.getElementById('printLabel').textContent = data.label;
                
                hideLoading();
                window.print();
                loadSummary();
            } else {
                alert('Gagal: ' + data.message);
                hideLoading();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem');
            hideLoading();
        });
    }

    function loadSummary() {
        fetch('/anjungan/api/summary')
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    // Only update Loket and LoketVIP
                    ['Loket', 'LoketVIP'].forEach(type => {
                        const stats = data.summary[type];
                        if (!stats) return;
                        
                        const totalEl = document.getElementById('total-' + type);
                        const menungguEl = document.getElementById('menunggu-' + type);
                        const nextEl = document.getElementById('next-' + type);
                        
                        if (totalEl) totalEl.textContent = stats.total;
                        if (menungguEl) menungguEl.textContent = stats.menunggu;
                        if (nextEl) nextEl.textContent = (parseInt(stats.last_number) + 1);
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }

    loadSummary();
    setInterval(loadSummary, 10000);
    </script>
@endpush

@endsection