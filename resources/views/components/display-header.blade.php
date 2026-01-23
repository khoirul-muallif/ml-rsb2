@props([
    'config' => [],
    'logo' => null,
    'company' => 'Rumah Sakit',
    'date' => ''
])

<div class="display-header">
    <div class="header-left">
        @if($logo && file_exists(public_path($logo)))
            <img src="{{ asset($logo) }}" alt="Logo" class="logo-img">
        @else
            <i class="fa fa-hospital fa-3x" style="color: {{ $config['color']['from'] ?? '#667eea' }}"></i>
        @endif
        <div class="header-title">
            <h1>{{ $config['full_label'] ?? 'Display Antrian' }}</h1>
            <p>{{ $company }}</p>
        </div>
    </div>
    <div class="header-right">
        <div class="header-date">{{ $date }}</div>
        <div class="header-time" id="header-time-display">--:--:--</div>
    </div>
</div>

<style>
.display-header {
    background: rgba(255, 255, 255, 0.95);
    padding: 20px 40px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.logo-img {
    width: 70px;
    height: 70px;
    object-fit: contain;
}

.header-title {
    color: #333;
}

.header-title h1 {
    font-size: 32px;
    font-weight: 700;
    margin: 0;
    color: {{ $config['color']['from'] ?? '#667eea' }};
}

.header-title p {
    font-size: 18px;
    margin: 0;
    color: #666;
    font-weight: 600;
}

.header-right {
    text-align: right;
    color: #333;
}

.header-date {
    font-size: 16px;
    color: #d9534f;
    font-weight: 700;
}

.header-time {
    font-size: 28px;
    color: {{ $config['color']['from'] ?? '#667eea' }};
    font-weight: 700;
}

@media (max-width: 768px) {
    .display-header {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .header-left {
        flex-direction: column;
    }
    
    .header-right {
        text-align: center;
    }
}
</style>

<script>
// Update clock in header
function updateHeaderClock() {
    const now = new Date();
    const time = now.toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
    });
    const el = document.getElementById('header-time-display');
    if (el) el.textContent = time;
}

setInterval(updateHeaderClock, 1000);
updateHeaderClock();
</script>