@props([
    'icon' => 'fa-door-open',
    'title' => 'TITLE',
    'subtitle' => 'Subtitle',
    'href' => '#',
    'colorFrom' => '#667eea',
    'colorTo' => '#764ba2',
    'badge' => null,
    'isDisplay' => false
])

@php
    $cardClass = $isDisplay ? 'dashboard-card-display' : 'dashboard-card';
@endphp

<a href="{{ $href }}" 
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
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    display: block;
    position: relative;
    overflow: hidden;
    color: inherit;
}

.dashboard-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    color: inherit;
    text-decoration: none;
}

.dashboard-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(90deg, var(--color-from), var(--color-to));
}

.card-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #ffd700, #ff9800);
    color: #000;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.card-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    color: #fff;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, var(--color-from), var(--color-to));
}

.card-title {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    text-align: center;
    margin-bottom: 10px;
}

.card-subtitle {
    text-align: center;
    color: #666;
    font-size: 14px;
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
    .card-title {
        font-size: 20px;
    }
    
    .card-icon {
        width: 60px;
        height: 60px;
        font-size: 28px;
    }
}
</style>