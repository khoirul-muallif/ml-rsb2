@extends('layouts.app')

@section('title', 'Pilih Display Monitor')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/partials/global.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
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
        
        .page-header {
            text-align: center;
            color: #fff;
            margin-bottom: 50px;
            padding: 20px;
        }
        
        .page-header h1 {
            font-size: 48px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 10px;
        }
        
        .page-header p {
            font-size: 20px;
            opacity: 0.95;
        }

        .section-title {
            color: #fff;
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            margin: 40px 0 25px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .display-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .display-card-all {
            grid-column: 1 / -1;
            background: rgba(255,255,255,0.98);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
            min-height: 150px;
        }

        .display-card-all:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.4);
            color: inherit;
            text-decoration: none;
        }

        .display-card-all .card-icon {
            width: 100px;
            height: 100px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #fff;
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .display-card-all .card-content {
            text-align: left;
        }

        .display-card-all .card-title {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .display-card-all .card-subtitle {
            font-size: 16px;
            color: #666;
        }

        @media (max-width: 768px) {
            .display-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .page-header h1 {
                font-size: 32px;
            }

            .page-header p {
                font-size: 18px;
            }

            .display-card-all {
                flex-direction: column;
                text-align: center;
                padding: 30px;
            }

            .display-card-all .card-content {
                text-align: center;
            }

            .display-card-all .card-icon {
                width: 80px;
                height: 80px;
                font-size: 40px;
            }

            .display-card-all .card-title {
                font-size: 24px;
            }
        }
    </style>
@endpush

@section('content')
<div class="main-container">
    <!-- Back Button -->
    <div class="back-button" onclick="window.location.href='{{ route('anjungan.index') }}'">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Dashboard</span>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-tv"></i> Monitor Display Antrian</h1>
        <p>Pilih display yang akan ditampilkan di TV/Monitor ruang tunggu</p>
    </div>

    <!-- ========================================== -->
    <!-- DISPLAY ALL (GABUNGAN) -->
    <!-- ========================================== -->
    <div class="section-title">
        <i class="fas fa-th-large"></i> Display Gabungan (Semua Loket)
    </div>
    
    <div class="display-grid">
        <a href="{{ route('anjungan.display') }}" class="display-card-all" target="_blank">
            <div class="card-icon">
                <i class="fas fa-th-large"></i>
            </div>
            <div class="card-content">
                <div class="card-title">DISPLAY SEMUA LOKET</div>
                <div class="card-subtitle">Tampilkan semua antrian dari Loket, CS, dan Apotek dalam satu layar</div>
            </div>
        </a>
    </div>

    <!-- ========================================== -->
    <!-- SECTION: LOKET PENDAFTARAN -->
    <!-- ========================================== -->
    <div class="section-title">
        <i class="fas fa-door-open"></i> Loket Pendaftaran
    </div>
    
    <div class="display-grid">
        <!-- Display Loket Reguler -->
        <x-anjungan.dashboard-card 
            icon="fa-door-open"
            title="DISPLAY LOKET"
            subtitle="Monitor untuk Loket Pendaftaran Reguler"
            href="{{ route('anjungan.display', ['show' => 'loket']) }}"
            colorFrom="#28a745"
            colorTo="#20c997"
            target="_blank" />

        <!-- Display Loket VIP -->
        <x-anjungan.dashboard-card 
            icon="fa-crown"
            title="DISPLAY LOKET VIP"
            subtitle="Monitor untuk Loket Pendaftaran VIP"
            href="{{ route('anjungan.display', ['show' => 'loket_vip']) }}"
            colorFrom="#198754"
            colorTo="#0d6efd"
            badge="VIP"
            target="_blank" />
    </div>

    <!-- ========================================== -->
    <!-- SECTION: CUSTOMER SERVICE -->
    <!-- ========================================== -->
    <div class="section-title">
        <i class="fas fa-headset"></i> Customer Service
    </div>
    
    <div class="display-grid">
        <!-- Display CS Reguler -->
        <x-anjungan.dashboard-card 
            icon="fa-headset"
            title="DISPLAY CS"
            subtitle="Monitor untuk Customer Service Reguler"
            href="{{ route('anjungan.display', ['show' => 'cs']) }}"
            colorFrom="#20c997"
            colorTo="#17a2b8"
            target="_blank" />

        <!-- Display CS VIP -->
        <x-anjungan.dashboard-card 
            icon="fa-concierge-bell"
            title="DISPLAY CS VIP"
            subtitle="Monitor untuk Customer Service VIP"
            href="{{ route('anjungan.display', ['show' => 'cs_vip']) }}"
            colorFrom="#0d9488"
            colorTo="#0891b2"
            badge="VIP"
            target="_blank" />
    </div>

    <!-- ========================================== -->
    <!-- SECTION: APOTEK -->
    <!-- ========================================== -->
    <div class="section-title">
        <i class="fas fa-pills"></i> Apotek & Farmasi
    </div>
    
    <div class="display-grid">
        <!-- Display Apotek -->
        <x-anjungan.dashboard-card 
            icon="fa-pills"
            title="DISPLAY APOTEK"
            subtitle="Monitor untuk Apotek & Farmasi"
            href="{{ route('anjungan.display', ['show' => 'apotek']) }}"
            colorFrom="#10b981"
            colorTo="#14b8a6"
            target="_blank" />
    </div>

    <!-- Info Box -->
    <div style="margin-top: 50px; text-align: center; color: rgba(255,255,255,0.9); padding: 20px;">
        <i class="fas fa-info-circle" style="font-size: 24px; margin-bottom: 10px;"></i>
        <p style="font-size: 16px; max-width: 600px; margin: 0 auto;">
            <strong>Catatan:</strong> Display ini akan menampilkan nomor antrian yang sedang dipanggil secara real-time. 
            Pastikan TV/Monitor sudah terhubung dan browser dalam mode full-screen (tekan F11).
        </p>
    </div>
</div>

@endsection