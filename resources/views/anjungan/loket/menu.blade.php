@extends('layouts.app')

@section('title', 'Anjungan Mandiri - Pilih Layanan')

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
            margin: 60px auto;
            padding: 0 20px;
        }

        .welcome-text {
            text-align: center;
            color: #fff;
            margin-bottom: 60px;
        }

        .welcome-text h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .welcome-text p {
            font-size: 22px;
            opacity: 0.95;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .menu-card {
            background: rgba(255,255,255,0.98);
            border-radius: 25px;
            padding: 50px 40px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            border: 5px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .menu-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: var(--gradient);
            transition: height 0.3s ease;
        }

        .menu-card:hover::before {
            height: 100%;
            opacity: 0.1;
        }

        .menu-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
            border-color: var(--border-color);
        }

        .menu-icon {
            font-size: 80px;
            margin-bottom: 25px;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
        }

        .menu-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 12px;
        }

        .menu-desc {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .menu-services {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .service-tag {
            background: rgba(0,0,0,0.05);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            color: #555;
            font-weight: 600;
        }

        /* Color schemes */
        .menu-card.loket {
            --gradient: linear-gradient(135deg, #10b981, #059669);
            --border-color: #10b981;
        }

        .menu-card.cs {
            --gradient: linear-gradient(135deg, #3b82f6, #2563eb);
            --border-color: #3b82f6;
        }

        .menu-card.apotek {
            --gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --border-color: #f59e0b;
        }

        @media (max-width: 768px) {
            .menu-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .welcome-text h1 {
                font-size: 32px;
            }

            .welcome-text p {
                font-size: 18px;
            }

            .menu-card {
                padding: 40px 30px;
            }

            .menu-icon {
                font-size: 64px;
            }

            .menu-title {
                font-size: 24px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Header -->
    <x-header 
        :logo="$logo ?? null"
        :title="$nama_instansi"
        :subtitle="$alamat ?? ''"
        :showTime="false"
    />

    <!-- Main Container -->
    <div class="main-container">
        <!-- Welcome -->
        <div class="welcome-text">
            <h1>Selamat Datang di Anjungan Mandiri</h1>
            <p>Silakan pilih kategori layanan yang Anda butuhkan</p>
        </div>

        <!-- Menu Grid -->
        <div class="menu-grid">
            <!-- Loket Pendaftaran -->
            <div class="menu-card loket" onclick="window.location.href='/anjungan/loket'">
                <div class="menu-icon">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="menu-title">Loket Pendaftaran</div>
                <div class="menu-desc">
                    Layanan pendaftaran pasien baru dan lama untuk mendapatkan nomor antrian
                </div>
                <div class="menu-services">
                    <span class="service-tag">Loket Reguler</span>
                    <span class="service-tag">Loket VIP</span>
                </div>
            </div>

            <!-- Customer Service -->
            <div class="menu-card cs" onclick="window.location.href='/anjungan/cs'">
                <div class="menu-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="menu-title">Customer Service</div>
                <div class="menu-desc">
                    Layanan informasi, konsultasi, dan bantuan untuk kebutuhan Anda
                </div>
                <div class="menu-services">
                    <span class="service-tag">CS Reguler</span>
                    <span class="service-tag">CS VIP</span>
                </div>
            </div>

            <!-- Apotek/Farmasi -->
            <div class="menu-card apotek" onclick="window.location.href='/anjungan/apotek'">
                <div class="menu-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <div class="menu-title">Apotek & Farmasi</div>
                <div class="menu-desc">
                    Layanan pengambilan obat dan konsultasi farmasi
                </div>
                <div class="menu-services">
                    <span class="service-tag">Apotek Umum</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Running Text -->
    <x-running-text :text="$running_text" speed="30" />

    <!-- Footer -->
    <x-footer :company="$nama_instansi" powered="mLITE" />

@endsection