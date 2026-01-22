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

    <div class="loket-grid">
        <!-- Loket Reguler -->
        <x-dashboard-card 
            icon="fa-door-open"
            title="LOKET"
            subtitle="Loket Pendaftaran Reguler"
            href="{{ route('anjungan.pemanggil', ['show' => 'panggil_loket', 'loket' => 1]) }}"
            colorFrom="#28a745"
            colorTo="#20c997" />

        <!-- Loket VIP -->
        <x-dashboard-card 
            icon="fa-crown"
            title="LOKET VIP"
            subtitle="Loket Pendaftaran VIP"
            href="{{ route('anjungan.pemanggil', ['show' => 'panggil_loket_vip', 'loket' => 1]) }}"
            colorFrom="#ffd700"
            colorTo="#ff9800"
            badge="VIP" />

        <!-- CS Reguler -->
        <x-dashboard-card 
            icon="fa-headset"
            title="CUSTOMER SERVICE"
            subtitle="Layanan Pelanggan Reguler"
            {{-- href="{{ url('/anjungan/pemanggil?show=panggil_cs&loket=1') }}" --}}
            href="{{ route('anjungan.pemanggil', ['show' => 'panggil_cs', 'loket' => 1]) }}"
            colorFrom="#007bff"
            colorTo="#0056b3" />

        <!-- CS VIP -->
        <x-dashboard-card 
            icon="fa-concierge-bell"
            title="CS VIP"
            subtitle="Layanan Pelanggan VIP"
            href="{{ route('anjungan.pemanggil', ['show' => 'panggil_cs_vip', 'loket' => 1]) }}"
            colorFrom="#6f42c1"
            colorTo="#5a32a3"
            badge="VIP" />

        <!-- Apotek -->
        <x-dashboard-card 
            icon="fa-pills"
            title="APOTEK"
            subtitle="Farmasi & Obat-Obatan"
            href="{{ route('anjungan.pemanggil', ['show' => 'panggil_apotek', 'loket' => 1]) }}"
            colorFrom="#ffc107"
            colorTo="#ff9800" />

        <!-- Display Monitor -->
        <x-dashboard-card 
            icon="fa-tv"
            title="MONITOR DISPLAY"
            subtitle="Tampilan untuk TV / Monitor Ruang Tunggu"
            href="{{ url('/anjungan/display') }}"
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