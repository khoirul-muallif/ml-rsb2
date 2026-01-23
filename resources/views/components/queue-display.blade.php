@props([
    'config' => [],
])

<div class="queue-display">
    @if(isset($config['badge']))
    <div class="queue-badge-vip">{{ $config['badge'] }}</div>
    @endif
    
    <div class="queue-label">
        <i class="fa {{ $config['icon'] }}"></i> {{ $config['label'] }}
    </div>
    
    <div class="queue-number-display">
        <span class="queue-prefix">{{ $config['prefix'] }}</span>
        <span class="queue-number" id="current-number">-</span>
    </div>
    
    <div class="queue-counter-display">
        KONTER <span class="queue-counter-number" id="current-counter">-</span>
    </div>
</div>

<style>
.queue-display {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    text-align: center;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    border: 5px solid {{ $config['color']['from'] }};
    position: relative;
}

.queue-badge-vip {
    position: absolute;
    top: -15px;
    right: 30px;
    background: linear-gradient(135deg, #ffd700, #ff9800);
    color: #000;
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 700;
    box-shadow: 0 4px 10px rgba(255, 215, 0, 0.5);
}

.queue-label {
    font-size: 32px;
    font-weight: 700;
    color: {{ $config['color']['from'] }};
    margin-bottom: 20px;
    text-transform: uppercase;
}

.queue-number-display {
    font-size: 160px;
    font-weight: 300;
    line-height: 1;
    color: {{ $config['color']['from'] }};
    margin: 30px 0;
    text-shadow: 4px 4px 8px rgba(0,0,0,0.1);
}
.queue-number {
    font-weight: 700;
}

.queue-prefix {
    font-weight: 700;
}


.queue-counter-display {
    font-size: 40px;
    color: #666;
    font-weight: 600;
    margin-top: 20px;
}

.queue-counter-number {
    color: {{ $config['color']['from'] }};
    font-weight: 700;
    font-size: 50px;
}

@media (max-width: 768px) {
    .queue-display {
        padding: 30px 20px;
    }
    
    .queue-label {
        font-size: 24px;
    }
    
    .queue-number-display {
        font-size: 100px;
    }
    
    .queue-counter-display {
        font-size: 28px;
    }
    
    .queue-counter-number {
        font-size: 36px;
    }
}
</style>