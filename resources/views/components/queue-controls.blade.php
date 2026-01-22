@props([
    'antrian' => 0,
    'loket' => 1,
    'config' => [],
    'panggil_loket' => ''
])

<div class="btn-group-justified">
    <button type="button" class="btn btn-info" disabled>
        {{ $antrian }}
    </button>
    
    <button type="button" 
            class="btn btn-warning" 
            onclick="panggil({{ $antrian }}, '{{ $loket }}', '{{ $config['audio_name'] ?? 'a' }}')">
        <i class="fa fa-bullhorn"></i> Panggil
    </button>
    
    <a href="{{ url('/anjungan/pemanggil?show='.$panggil_loket.'&loket='.$loket) }}" 
       class="btn btn-success">
        <i class="fa fa-forward"></i> Berikutnya
    </a>
</div>

<style>
.btn-group-justified {
    display: flex;
    gap: 10px;
}

.btn-group-justified .btn {
    flex: 1;
    padding: 12px;
    font-size: 1.25rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: #fff;
}

.btn-group-justified .btn:hover:not(:disabled) {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.btn-info {
    background: #17a2b8;
}

.btn-warning {
    background: #ffc107;
    color: #000;
}

.btn-success {
    background: #28a745;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .btn-group-justified {
        flex-direction: column;
    }
}
</style>