@extends('layouts.app')

@section('title', 'Anjungan Mandiri')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/partials/global.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .main-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-text {
            text-align: center;
            color: #fff;
            margin-bottom: 50px;
        }

        .welcome-text h2 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .welcome-text p {
            font-size: 18px;
            opacity: 0.9;
        }

        /* ✅ Group Container */
        .service-group {
            margin-bottom: 50px;
        }

        .group-title {
            text-align: center;
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 25px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .loket-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        @media (max-width: 768px) {
            .loket-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .group-title {
                font-size: 22px;
            }

            .welcome-text h2 {
                font-size: 28px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Header -->
    <x-anjungan.header 
        :logo="$logo ?? null"
        :title="$nama_instansi"
        :subtitle="$alamat ?? ''"
        :showTime="false"
    />

    <!-- Main Container -->
    <div class="main-container">
        <!-- Welcome -->
        <div class="welcome-text">
            <h2>Selamat Datang di Anjungan Mandiri</h2>
            <p>Silakan pilih layanan yang Anda butuhkan</p>
        </div>

        <!-- GROUP 1: LOKET -->
        <div class="service-group">
            <div class="group-title">
                <i class="fas fa-door-open"></i> LOKET PENDAFTARAN
            </div>
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

        <!-- GROUP 2: CUSTOMER SERVICE -->
        <div class="service-group">
            <div class="group-title">
                <i class="fas fa-headset"></i> CUSTOMER SERVICE
            </div>
            <div class="loket-grid">
                @foreach(['CS', 'CSVIP'] as $type)
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

        <!-- GROUP 3: APOTEK/FARMASI -->
        <div class="service-group">
            <div class="group-title">
                <i class="fas fa-pills"></i> APOTEK & FARMASI
            </div>
            <div class="loket-grid">
                @foreach(['Apotek'] as $type)
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
    </div>

    <!-- Running Text -->
    <x-anjungan.running-text :text="$running_text" speed="30" />

    <!-- Footer -->
    <x-anjungan.footer :company="$nama_instansi" powered="mLITE" />

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
        
        fetch('/anjungan/loket/ambil', {
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
                // Set print data
                document.getElementById('printNumber').textContent = data.display;
                document.getElementById('printLabel').textContent = data.label;
                
                hideLoading();
                
                // Print
                window.print();
                
                // Refresh summary
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
        fetch('/anjungan/loket/summary')
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    Object.keys(data.summary).forEach(type => {
                        const stats = data.summary[type];
                        
                        const totalEl = document.getElementById('total-' + type);
                        const menungguEl = document.getElementById('menunggu-' + type);
                        const nextEl = document.getElementById('next-' + type);
                        
                        if (totalEl) totalEl.textContent = stats.total;
                        if (menungguEl) menungguEl.textContent = stats.menunggu;
                        // ✅ FIXED: Convert to number first to prevent string concatenation
                        if (nextEl) nextEl.textContent = (parseInt(stats.last_number) + 1);
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Load on page load
    loadSummary();

    // Auto refresh every 10 seconds
    setInterval(loadSummary, 10000);
    </script>
@endpush

@endsection