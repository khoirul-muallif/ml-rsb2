{{-- Loket Service Card Component dengan Nomor Selanjutnya --}}
@props([
    'config' => [],
    'type' => 'loket',
    'stats' => [],
    'nextNumber' => 0
])

<div class="loket-card" style="--color-from: {{ $config['color']['from'] }}; --color-to: {{ $config['color']['to'] }};">
    {{-- @if(isset($config['badge']))
        <div class="loket-badge">{{ $config['badge'] }}</div>
    @endif --}}
    
    <div class="loket-icon">
        <i class="fa {{ $config['icon'] }}"></i>
    </div>
    
    <div class="loket-label">{{ $config['label'] }}</div>
    <div class="loket-desc">{{ $config['full_label'] }}</div>
    
    <!-- Nomor Selanjutnya -->
    <div class="next-number-section">
        <span class="next-label">Nomor Selanjutnya</span>
        <span class="next-number" style="color: {{ $config['color']['from'] }}">
            {{ $config['prefix'] }}<span id="next-{{ $type }}">{{ $nextNumber + 1 }}</span>
            {{-- ✅ UBAH dari str_pad() ke langsung $nextNumber + 1 --}}
        </span>
    </div>
    
    <!-- Stats -->
    {{-- <div class="loket-stats">
        <div class="stat-item">
            <div class="stat-number" id="total-{{ $type }}">{{ $stats['total'] ?? 0 }}</div>
            <div class="stat-label">Total Hari Ini</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" id="menunggu-{{ $type }}">{{ $stats['menunggu'] ?? 0 }}</div>
            <div class="stat-label">Menunggu</div>
        </div>
    </div> --}}
</div>

<style>
.loket-card {
    background: rgba(255,255,255,0.95);
    border-radius: 20px;
    padding: 40px 30px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    border: 4px solid transparent;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.loket-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, var(--color-from), var(--color-to));
}

.loket-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 50px rgba(0,0,0,0.3);
    border-color: var(--color-from);
}

.loket-badge {
    display: inline-block;
    background: linear-gradient(135deg, #ffd700, #ff9800);
    color: #000;
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 15px;
}

.loket-icon {
    font-size: 64px;
    margin-bottom: 20px;
    background: linear-gradient(135deg, var(--color-from), var(--color-to));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.loket-label {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.loket-desc {
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
}

/* ✅ Nomor Selanjutnya */
.next-number-section {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin: 20px 0;
    padding: 20px;
    background: rgba(0,0,0,0.03);
    border-radius: 12px;
    border: 2px dashed var(--color-from);
}

.next-label {
    font-size: 12px;
    color: #999;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.next-number {
    font-size: 42px;
    font-weight: 700;
    line-height: 1;
    font-family: 'Arial', sans-serif;
}

/* Stats */
.loket-stats {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: var(--color-from);
}

.stat-label {
    font-size: 12px;
    color: #999;
    text-transform: uppercase;
}

@media (max-width: 768px) {
    .loket-card {
        padding: 25px 20px;
    }
    
    .next-number {
        font-size: 36px;
    }
    
    .loket-icon {
        font-size: 48px;
    }
}
</style>