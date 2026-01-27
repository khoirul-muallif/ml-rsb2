@extends('layouts.app')

@section('title', 'Pilih Loket Pemanggil')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/partials/global.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        
        .loket-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        @media (max-width: 768px) {
            .loket-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .page-header h1 {
                font-size: 32px;
            }

            .page-header p {
                font-size: 18px;
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
        <h1><i class="fas fa-user-tie"></i> Panel Pemanggil Antrian</h1>
        <p>Pilih loket yang akan Anda operasikan</p>
    </div>

    <!-- ========================================== -->
    <!-- SECTION: LOKET PENDAFTARAN -->
    <!-- ========================================== -->
    <div class="section-title">
        <i class="fas fa-door-open"></i> Loket Pendaftaran
    </div>
    
    <div class="loket-grid">
        <!-- Loket Reguler -->
        <x-anjungan.dashboard-card 
            icon="fa-door-open"
            title="LOKET"
            subtitle="Loket Pendaftaran Reguler"
            href="{{ route('anjungan.pemanggil', ['show' => 'panggil_loket', 'loket' => 1]) }}"
            colorFrom="#28a745"
            colorTo="#20c997" />

        <!-- Loket VIP -->
        <x-anjungan.dashboard-card 
            icon="fa-crown"
            title="LOKET VIP"
            subtitle="Loket Pendaftaran VIP"
            href="{{ route('anjungan.pemanggil', ['show' => 'panggil_loket_vip', 'loket' => 1]) }}"
            colorFrom="#ffd700"
            colorTo="#ff9800"
            badge="VIP" />
    </div>

    <!-- ========================================== -->
    <!-- SECTION: CUSTOMER SERVICE -->
    <!-- ========================================== -->
    <div class="section-title">
        <i class="fas fa-headset"></i> Customer Service
    </div>
    
    <div class="loket-grid">
        <!-- CS Reguler -->
        <x-anjungan.dashboard-card 
            icon="fa-headset"
            title="CUSTOMER SERVICE"
            subtitle="Layanan Pelanggan Reguler"
            href="{{ route('anjungan.pemanggil', ['show' => 'panggil_cs', 'loket' => 1]) }}"
            colorFrom="#007bff"
            colorTo="#0056b3" />

        <!-- CS VIP -->
        <x-anjungan.dashboard-card 
            icon="fa-concierge-bell"
            title="CS VIP"
            subtitle="Layanan Pelanggan VIP"
            href="{{ route('anjungan.pemanggil', ['show' => 'panggil_cs_vip', 'loket' => 1]) }}"
            colorFrom="#6f42c1"
            colorTo="#5a32a3"
            badge="VIP" />
    </div>

    <!-- ========================================== -->
    <!-- SECTION: APOTEK -->
    <!-- ========================================== -->
    <div class="section-title">
        <i class="fas fa-pills"></i> Apotek & Farmasi
    </div>
    
    <div class="loket-grid">
        <!-- Apotek -->
        <x-anjungan.dashboard-card 
            icon="fa-pills"
            title="APOTEK"
            subtitle="Farmasi & Obat-Obatan"
            href="{{ route('anjungan.pemanggil', ['show' => 'panggil_apotek', 'loket' => 1]) }}"
            colorFrom="#ffc107"
            colorTo="#ff9800" />
    </div>
</div>

@endsection