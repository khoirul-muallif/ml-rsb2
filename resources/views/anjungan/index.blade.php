@extends('layouts.app')

@section('title', 'Dashboard Antrian')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/partials/global.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        
        .dashboard-header {
            text-align: center;
            color: #fff;
            margin-bottom: 50px;
            padding: 40px 20px;
        }
        
        .dashboard-header h1 {
            font-size: 56px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 10px;
        }
        
        .dashboard-header p {
            font-size: 20px;
            opacity: 0.9;
        }
        
        .container-dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .section-title {
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            margin: 50px 0 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .loket-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
    </style>
@endpush

@section('content')
<div class="container-dashboard">
    <div class="dashboard-header">
        <h1><i class="fas fa-hospital"></i> Sistem Antrian</h1>
        <p id="current-datetime"></p>
    </div>

    <!-- ========================================== -->
    <!-- SECTION: ANJUNGAN PASIEN -->
    <!-- ========================================== -->
    <div class="section-title">
        <i class="fas fa-users"></i> Anjungan Mandiri Pasien
    </div>
    
    <div class="loket-grid">
        <!-- Menu Anjungan Pasien -->
        <x-anjungan.dashboard-card 
            icon="fa-ticket-alt"
            title="ANJUNGAN PASIEN"
            subtitle="Ambil Nomor Antrian (Loket, CS, Apotek)"
            href="{{ route('anjungan.layanan.menu') }}"
            colorFrom="#28a745"
            colorTo="#20c997" />
    </div>

    <!-- ========================================== -->
    <!-- SECTION: PEMANGGIL ANTRIAN (PETUGAS) -->
    <!-- ========================================== -->
    <div class="section-title">
        <i class="fas fa-user-tie"></i> Panel Petugas Loket
    </div>
    
    <div class="loket-grid">
        <!-- Menu Pemanggil -->
        <x-anjungan.dashboard-card 
            icon="fa-bullhorn"
            title="PANEL PEMANGGIL"
            subtitle="Pemanggil Antrian untuk Petugas (Loket, CS, Apotek)"
            href="{{ route('anjungan.pemanggil.menu') }}"
            colorFrom="#dc3545"
            colorTo="#c82333" />
    </div>

    <!-- ========================================== -->
    <!-- SECTION: DISPLAY MONITOR -->
    <!-- ========================================== -->
    <div class="section-title">
        <i class="fas fa-tv"></i> Monitor Display
    </div>
    
    <div class="loket-grid">
        <!-- Menu Display -->
        <x-anjungan.dashboard-card 
            icon="fa-tv"
            title="MONITOR DISPLAY"
            subtitle="Tampilan untuk TV / Monitor Ruang Tunggu (Semua Loket)"
            href="{{ route('anjungan.display.menu') }}"
            colorFrom="#17a2b8"
            colorTo="#138496"
            isDisplay="true" />
    </div>
</div>

@push('scripts')
<script>
function updateDateTime() {
    const now = new Date();
    document.getElementById('current-datetime').textContent = 
        now.toLocaleDateString('id-ID', { 
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }) + ' • ' +
        now.toLocaleTimeString('id-ID', { 
            hour: '2-digit',
            minute: '2-digit'
        });
}

updateDateTime();
setInterval(updateDateTime, 1000);
</script>
@endpush

@endsection