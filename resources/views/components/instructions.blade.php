{{-- Generic Instructions/Help Component --}}
@props([
    'title' => 'Panduan Penggunaan',
    'items' => [],
    'bgClass' => 'bg-light',
    'borderColor' => '#667eea'
])

<div class="instructions-section" style="background-color: {{ str_replace('#', 'rgba(', str_replace('ff)', 'ff, 0.1)', $borderColor)) }}; border-left: 5px solid {{ $borderColor }};">
    @if($title)
        <h3 class="instructions-title">
            <i class="fas fa-book"></i> {{ $title }}
        </h3>
    @endif

    <ul class="instructions-list">
        @forelse($items as $item)
            <li class="instruction-item">
                @if(isset($item['icon']))
                    <i class="fas {{ $item['icon'] }}"></i>
                @endif
                
                <div class="instruction-content">
                    @if(isset($item['title']))
                        <strong>{{ $item['title']}}:</strong>
                    @endif
                    {{ $item['text'] }}
                </div>
            </li>
        @empty
            <li class="instruction-item">
                <p style="color: #999; margin: 0;">Tidak ada panduan untuk ditampilkan</p>
            </li>
        @endforelse
    </ul>

    @if($slot && !empty($slot))
        <div class="instruction-extra">
            {{ $slot }}
        </div>
    @endif
</div>

<style>
.instructions-section {
    border-radius: 15px;
    padding: 30px;
    margin: 30px auto;
    max-width: 1200px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    background-color: rgba(102, 126, 234, 0.05);
}

.instructions-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 20px;
    margin-top: 0;
    color: #333;
}

.instructions-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.instruction-item {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    padding: 15px;
    background: rgba(255,255,255,0.8);
    border-radius: 10px;
    border-left: 4px solid #667eea;
}

.instruction-item:last-child {
    margin-bottom: 0;
}

.instruction-item i {
    font-size: 20px;
    color: #667eea;
    flex-shrink: 0;
    margin-top: 2px;
    min-width: 24px;
    text-align: center;
}

.instruction-content {
    font-size: 15px;
    color: #555;
    line-height: 1.6;
}

.instruction-content strong {
    color: #333;
}

.instruction-extra {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .instructions-section {
        margin: 20px 0;
        padding: 20px;
    }
    
    .instructions-title {
        font-size: 18px;
    }
    
    .instruction-item {
        padding: 12px;
        gap: 10px;
    }
}
</style>