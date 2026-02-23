{{-- E:\laragon\www\ml-rsb2\resources\views\components\queue-controls.blade.php --}}
@props([
    'antrian' => 0,
    'loket' => 1,
    'config' => [],
    'panggil_loket' => '',
    'max_antrian' => 0
])

<div class="control-wrapper">
    {{-- Action Buttons --}}
    <div class="btn-group-justified">
        {{-- Info Button: Total Hari Ini --}}
        <button type="button" class="btn btn-info" disabled title="Total antrian hari ini">
            {{-- <i class="fa fa-list-ol"></i>  --}}
            <div class="btn-content">
                <span class="btn-label">Total:</span>
                <strong class="btn-value">{{ $max_antrian }}</strong>
            </div>
        </button>
        
        {{-- Panggil Button (with Audio) --}}
        <button type="button" 
                class="btn btn-warning" 
                onclick="panggil({{ $antrian }}, '{{ $loket }}', '{{ $config['audio_name'] ?? 'a' }}')"
                @if($antrian <= 0) disabled @endif
                title="Panggil ulang nomor {{ $config['prefix'] ?? 'A' }}{{ $antrian }} dengan suara">
            <i class="fa fa-bullhorn"></i> Panggil
        </button>
        
        {{-- Next Button (NO Audio, just redirect) --}}
        {{-- ✅ FIXED: Tambah &action=next untuk deteksi klik berikutnya --}}
        <a href="{{ url('/anjungan/pemanggil?show='.$panggil_loket.'&loket='.$loket.'&action=next') }}" 
           class="btn btn-success"
           @if($max_antrian <= 0) 
               onclick="event.preventDefault(); alert('Belum ada antrian');"
               style="opacity: 0.5; cursor: not-allowed;"
           @endif
           title="Panggil nomor berikutnya ({{ $config['prefix'] ?? 'A' }}{{ $antrian + 1 }})">
            <i class="fa fa-forward"></i> Berikutnya
        </a>
    </div>

    {{-- Statistics Bar --}}
    <div class="stats-bar">
        <div class="stat-item">
            <i class="fa fa-clock text-warning"></i>
            <span>Menunggu: <strong id="stat-menunggu">-</strong></span>
        </div>
        <div class="stat-item">
            <i class="fa fa-spinner text-info"></i>
            <span>Diproses: <strong id="stat-diproses">-</strong></span>
        </div>
        <div class="stat-item">
            <i class="fa fa-check-circle text-success"></i>
            <span>Selesai: <strong id="stat-selesai">-</strong></span>
        </div>
    </div>
</div>

<style>
.control-wrapper {
    margin: 0;
}

/* Button Group */
.btn-group-justified {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.btn-group-justified .btn {
    flex: 1;
    padding: 15px 20px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 5px;
    color: #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    min-height: 80px;
}

.btn-group-justified .btn i {
    font-size: 1.5rem;
}

.btn-group-justified .btn:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
}

.btn-group-justified .btn:active:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8, #138496);
    cursor: default;
}

.btn-info .btn-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
}

.btn-info .btn-label {
    font-size: 0.85rem;
    font-weight: 400;
    opacity: 0.9;
}

.btn-info .btn-value {
    font-size: 1.8rem;
    font-weight: 700;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107, #ff9800);
    color: #000;
    font-weight: 700;
}

.btn-warning:hover:not(:disabled) {
    background: linear-gradient(135deg, #ffb300, #ff8c00);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #218838);
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838, #1e7e34);
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1) !important;
}

/* Pulse animation untuk tombol Panggil */
@keyframes pulse-button {
    0%, 100% {
        box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3);
    }
    50% {
        box-shadow: 0 4px 25px rgba(255, 193, 7, 0.7);
    }
}

.btn-warning:not(:disabled) {
    animation: pulse-button 2s infinite;
}

/* Statistics Bar */
.stats-bar {
    display: flex;
    justify-content: space-around;
    background: rgba(255,255,255,0.5);
    border-radius: 8px;
    padding: 12px;
    gap: 10px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #666;
}

.stat-item i {
    font-size: 16px;
}

.stat-item span {
    white-space: nowrap;
}

.stat-item strong {
    font-size: 16px;
    color: #333;
    font-weight: 700;
}

.text-warning { color: #ffc107; }
.text-info { color: #17a2b8; }
.text-success { color: #28a745; }

/* Responsive */
@media (max-width: 768px) {
    .btn-group-justified {
        flex-direction: column;
    }
    
    .btn-group-justified .btn {
        width: 100%;
        min-height: 70px;
    }
    
    .stats-bar {
        flex-direction: column;
        gap: 8px;
    }
    
    .stat-item {
        justify-content: center;
    }
}
</style>

<script>
// Load statistics
function loadQueueStats(type) {
    fetch('/anjungan/api/getstats?type=' + type)
        .then(response => response.json())
        .then(data => {
            if (data.status && data.stats) {
                const menungguEl = document.getElementById('stat-menunggu');
                const diprosesEl = document.getElementById('stat-diproses');
                const selesaiEl = document.getElementById('stat-selesai');
                
                if (menungguEl) menungguEl.textContent = data.stats.menunggu || '0';
                if (diprosesEl) diprosesEl.textContent = data.stats.diproses || '0';
                if (selesaiEl) selesaiEl.textContent = data.stats.selesai || '0';
            }
        })
        .catch(error => {
            console.error('Error loading stats:', error);
        });
}

// Auto-load stats on component ready
document.addEventListener('DOMContentLoaded', function() {
    const typeElement = document.querySelector('[data-queue-type]');
    if (typeElement) {
        const queueType = typeElement.getAttribute('data-queue-type');
        loadQueueStats(queueType);
        
        // Refresh stats every 10 seconds
        setInterval(() => loadQueueStats(queueType), 10000);
    }
});
</script>