@props([
    'config' => [],
    'loket' => 1,
    'antrian' => '-',
    'panggil_loket' => ''
])

<div class="panel">
    <div class="panel-heading" style="background: linear-gradient(135deg, {{ $config['color']['from'] }} 0%, {{ $config['color']['to'] }} 100%);">
        <i class="fas {{ $config['icon'] }}"></i> Loket {{ $loket }}
    </div>
    
    <div class="panel-body text-center">
        <h2 class="panel-title">
            <span style="font-weight: 700;">{{ $config['prefix'] }}{{ $antrian }}</span>
        </h2>
        <p class="text-muted tengah mt-3">Nomor Antrian Saat Ini</p>
    </div>
    
    <div class="panel-footer">
        {{ $slot }}
    </div>
</div>

<style>
.tengah {
  text-align: center; /* Ini akan memusatkan teks secara horizontal */
}
.panel {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    overflow: hidden;
    transition: transform 0.3s ease;
    margin-bottom: 30px;
    border: 3px solid {{ $config['color']['from'] ?? '#667eea' }};
}

.panel:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
}

.panel-heading {
    color: #fff;
    padding: 15px;
    font-weight: 600;
    font-size: 24px;
    text-align: center;
}

.panel-body {
    padding: 40px 20px;
}

.panel-title {
    font-size: 72px;
    text-align: center;
    font-weight: 300;
    margin: 0;
    padding: 0;
    line-height: 1;
    color: {{ $config['color']['from'] ?? '#667eea' }};
}

.panel-footer {
    padding: 20px;
    background: #f8f9fa;
}

@media (max-width: 768px) {
    .panel-title {
        font-size: 48px;
    }
}
</style>