@props([
    'config' => [],
    'logo' => null,
    'company' => 'Rumah Sakit',
    'date' => ''
])

<div class="display-header">
    <div class="header-left">
        <div class="logo-container">
            @if($logo && file_exists(public_path($logo)))
                <img src="{{ asset($logo) }}" alt="Logo" class="logo-img">
            @else
                <i class="fa fa-hospital fa-3x" style="color: {{ $config['color']['from'] ?? '#667eea' }}"></i>
            @endif
        </div>
        <div class="header-title">
            <h1>{{ $config['full_label'] ?? 'Display Antrian' }}</h1>
            <p>{{ $company }}</p>
        </div>
    </div>
    <div class="header-right">
        <div class="time-info">
            <div class="header-date">{{ $date }}</div>
            <div class="header-time" id="header-time-display">--:--:--</div>
        </div>
    </div>
</div>

<style>
.display-header {
    background: rgba(255, 255, 255, 0.95);
    padding: 25px 40px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-radius: 20px;
    width: 97%;
    margin: 0 auto;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 20px;
    flex: 1;
}

.logo-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 70px;
    height: 70px;
}

.logo-img {
    width: 70px;
    height: 70px;
    object-fit: contain;
}

.header-title {
    color: #333;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.header-title h1 {
    font-size: 32px;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
    color: {{ $config['color']['from'] ?? '#667eea' }};
}

.header-title p {
    font-size: 18px;
    margin: 5px 0 0 0;
    color: #666;
    font-weight: 600;
    line-height: 1.2;
}

.header-right {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex: 1;
    min-width: 200px;
}

.time-info {
    text-align: right;
    color: #333;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.header-date {
    font-size: 16px;
    color: #d9534f;
    font-weight: 700;
    margin-bottom: 5px;
    line-height: 1.2;
}

.header-time {
    font-size: 28px;
    color: {{ $config['color']['from'] ?? '#667eea' }};
    font-weight: 700;
    line-height: 1.2;
}

@media (max-width: 992px) {
    .display-header {
        padding: 20px 30px;
    }
    
    .header-title h1 {
        font-size: 28px;
    }
    
    .header-title p {
        font-size: 16px;
    }
    
    .header-time {
        font-size: 24px;
    }
}

@media (max-width: 768px) {
    .display-header {
        flex-direction: column;
        text-align: center;
        gap: 20px;
        padding: 20px;
    }
    
    .header-left,
    .header-right {
        flex: unset;
        width: 100%;
        justify-content: center;
    }
    
    .header-left {
        flex-direction: column;
        gap: 15px;
    }
    
    .time-info {
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