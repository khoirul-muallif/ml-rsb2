@props([
    'config' => [],
    'logo' => null,
    'company' => 'Rumah Sakit',
    'date' => ''
])

<div class="display-header">
    <div class="display-header-left">
        @if($logo && file_exists(public_path($logo)))
            <img src="{{ asset($logo) }}" alt="Logo" class="display-header-logo">
        @else
            <i class="fas fa-hospital" style="font-size: 50px; opacity: 0.7;"></i>
        @endif
        
        <div class="display-header-info">
            <h1 class="display-header-title">{{ $config['full_label'] ?? 'Display Antrian' }}</h1>
            <p class="display-header-company">{{ $company }}</p>
        </div>
    </div>

    <div class="display-header-right">
        @if($date)
            <div class="display-header-date">{{ $date }}</div>
        @endif
        <div class="display-header-time" id="display-time">--:--:--</div>
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
    gap: 30px;
}

.display-header-left {
    display: flex;
    align-items: center;
    gap: 20px;
    flex: 1;
}

.display-header-logo {
    height: 70px;
    object-fit: contain;
}

.display-header-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.display-header-title {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    color: #333;
}

.display-header-company {
    font-size: 14px;
    margin: 0;
    color: #666;
    opacity: 0.8;
}

.display-header-right {
    text-align: right;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.display-header-date {
    font-size: 14px;
    color: #666;
    font-weight: 600;
}

.display-header-time {
    font-size: 24px;
    color: #333;
    font-weight: 700;
}

@media (max-width: 1024px) {
    .display-header {
        padding: 15px 20px;
        flex-wrap: wrap;
    }
    
    .display-header-title {
        font-size: 22px;
    }
    
    .display-header-time {
        font-size: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateTime() {
        const now = new Date();
        const timeEl = document.getElementById('display-time');
        if(timeEl) {
            timeEl.textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
    }
    
    updateTime();
    setInterval(updateTime, 1000);
});
</script>