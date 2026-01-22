@props([
    'config' => [],
    'antrian' => '-',
    'counter' => '-',
    'stats' => null
])

<div class="queue-display-card">
    <div class="queue-display-label">
        <i class="fa {{ $config['icon'] ?? 'fa-door-open' }}"></i>
        {{ $config['label'] ?? 'ANTRIAN' }}
    </div>
    
    <div class="queue-display-number" style="color: {{ $config['color']['from'] ?? '#0264d6' }}">
        <span class="prefix">{{ $config['prefix'] ?? 'A' }}</span>{{ $antrian }}
    </div>
    
    <div class="queue-display-counter">
        KONTER <span class="counter-number" style="color: {{ $config['color']['from'] ?? '#0264d6' }}">{{ $counter }}</span>
    </div>
    
    @if($stats)
        <div class="stats-mini">
            <div><strong>{{ $stats['total'] ?? 0 }}</strong> Total</div>
            <div><strong>{{ $stats['menunggu'] ?? 0 }}</strong> Tunggu</div>
        </div>
    @endif
</div>

<style>
.queue-display-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    text-align: center;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    border: 5px solid {{ $config['color']['from'] ?? '#0264d6' }};
}

.queue-display-label {
    font-size: 32px;
    font-weight: 700;
    color: {{ $config['color']['from'] ?? '#0264d6' }};
    margin-bottom: 20px;
    text-transform: uppercase;
}

.queue-display-number {
    font-size: 160px;
    font-weight: 300;
    line-height: 1;
    margin: 30px 0;
    text-shadow: 4px 4px 8px rgba(0,0,0,0.1);
}

.prefix {
    font-weight: 700;
}

.queue-display-counter {
    font-size: 40px;
    color: #666;
    font-weight: 600;
    margin-top: 20px;
}

.counter-number {
    font-weight: 700;
    font-size: 50px;
}

.stats-mini {
    display: flex;
    gap: 30px;
    margin-top: 30px;
    justify-content: center;
    font-size: 18px;
    color: #666;
}

.stats-mini strong {
    display: block;
    font-size: 36px;
    font-weight: 700;
    color: {{ $config['color']['from'] ?? '#0264d6' }};
}

@media (max-width: 1024px) {
    .queue-display-number {
        font-size: 120px;
    }
    
    .counter-number {
        font-size: 40px;
    }
}
</style>