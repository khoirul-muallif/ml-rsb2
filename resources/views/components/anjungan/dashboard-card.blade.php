@props([
    'icon' => 'fa-door-open',
    'title' => 'TITLE',
    'subtitle' => 'Subtitle',
    'href' => '#',
    'colorFrom' => '#667eea',
    'colorTo' => '#764ba2',
    'badge' => null,
    'isDisplay' => false,
    'target' => null,
])

@php
    $cardClass = $isDisplay ? 'dashboard-card-display' : 'dashboard-card';
@endphp

<a href="{{ $href }}"  target="{{ $target }}"
   class="{{ $cardClass }}"
   style="--color-from: {{ $colorFrom }}; --color-to: {{ $colorTo }};">
    
    @if($badge)
        <span class="card-badge">{{ $badge }}</span>
    @endif
    
    <div class="card-icon">
        <i class="fas {{ $icon }}"></i>
    </div>
    
    <div class="card-title">{{ $title }}</div>
    <div class="card-subtitle">{{ $subtitle }}</div>
</a>

<style>
.dashboard-card {
    background: #fff;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    display: block;
    position: relative;
    overflow: hidden;
    color: inherit;
}

.dashboard-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    color: inherit;
    text-decoration: none;
}

.dashboard-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--color-from), var(--color-to));
}

.card-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: linear-gradient(135deg, #ffd700, #ff9800);
    color: #000;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
}

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
    margin: 0 auto 12px;
    background: linear-gradient(135deg, var(--color-from), var(--color-to));
}

.card-title {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    text-align: center;
    margin-bottom: 6px;
}

.card-subtitle {
    text-align: center;
    color: #666;
    font-size: 12px;
    line-height: 1.4;
}

/* Display Card Style */
.dashboard-card-display {
    grid-column: 1 / -1;
    background: linear-gradient(135deg, var(--color-from), var(--color-to));
    color: #fff;
}

.dashboard-card-display::before {
    display: none;
}

.dashboard-card-display .card-icon {
    background: rgba(255,255,255,0.2);
}

.dashboard-card-display .card-title,
.dashboard-card-display .card-subtitle {
    color: #fff;
}

@media (max-width: 768px) {
    .dashboard-card {
        padding: 14px;
    }
    
    .card-title {
        font-size: 16px;
    }
    
    .card-icon {
        width: 45px;
        height: 45px;
        font-size: 22px;
    }
    
    .card-subtitle {
        font-size: 11px;
    }
}
</style>