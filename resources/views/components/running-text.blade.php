{{-- Running Text/Ticker Component --}}
@props([
    'text' => 'Selamat datang',
    'speed' => 30,
    'bgColor' => 'rgba(0,0,0,0.8)',
    'textColor' => '#fff'
])

<div class="running-text" style="background: {{ $bgColor }}; color: {{ $textColor }};">
    <div class="running-text-content" style="animation-duration: {{ $speed }}s">
        {{ $text }}
    </div>
</div>

<style>
.running-text {
    position: fixed;
    bottom: 50px;
    left: 0;
    right: 0;
    padding: 15px;
    overflow: hidden;
    height: 50px;
    display: flex;
    align-items: center;
    backdrop-filter: blur(10px);
    z-index: 50;
}

.running-text-content {
    white-space: nowrap;
    animation: scroll linear infinite;
    font-size: 18px;
    font-weight: 600;
}

@keyframes scroll {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}

@media (max-width: 768px) {
    .running-text {
        bottom: 40px;
    }
    
    .running-text-content {
        font-size: 14px;
    }
}
</style>