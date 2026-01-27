{{-- Generic Header Component --}}
@props([
    'logo' => null,
    'title' => '',
    'subtitle' => '',
    'showTime' => true,
    'showDate' => false,
    'navLinks' => [],
    'bgClass' => 'bg-white',
    'textColor' => 'text-dark'
])

<div class="shared-header {{ $bgClass }} {{ $textColor }}">
    <div class="header-container">
        <div class="header-left">
            @if($logo && file_exists(public_path($logo)))
                <img src="{{ asset($logo) }}" alt="Logo" class="header-logo">
            @endif
            
            <div class="header-info">
                @if($title)
                    <h1 class="header-title">{{ $title }}</h1>
                @endif
                @if($subtitle)
                    <p class="header-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
        </div>

        <div class="header-right">
            @if($showDate)
                <div class="header-date" id="date-display">-</div>
            @endif
            
            @if($showTime)
                <div class="header-time" id="time-display">--:--:--</div>
            @endif
        </div>
    </div>

    @if(count($navLinks) > 0)
        <div class="header-nav">
            @foreach($navLinks as $link)
                <a href="{{ $link['url'] }}" class="nav-item">
                    @if(isset($link['icon']))
                        <i class="fas {{ $link['icon'] }}"></i>
                    @endif
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>
    @endif
</div>

<style>
.shared-header {
    padding: 20px 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 30px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.header-logo {
    height: 70px;
    object-fit: contain;
}

.header-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.header-title {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
}

.header-subtitle {
    font-size: 14px;
    margin: 0;
    opacity: 0.7;
}

.header-right {
    text-align: right;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.header-date,
.header-time {
    font-weight: 700;
    font-size: 16px;
}

.header-nav {
    display: flex;
    gap: 20px;
    margin-top: 15px;
    width: 100%;
    border-top: 1px solid rgba(0,0,0,0.1);
    padding-top: 15px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    padding: 8px 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 14px;
    font-weight: 500;
}

.nav-item:hover {
    background: rgba(0,0,0,0.05);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateClock() {
        const now = new Date();
        const timeEl = document.getElementById('time-display');
        const dateEl = document.getElementById('date-display');
        
        if(timeEl) {
            timeEl.textContent = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
        }
        
        if(dateEl) {
            dateEl.textContent = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    }
    
    updateClock();
    setInterval(updateClock, 1000);
});
</script>