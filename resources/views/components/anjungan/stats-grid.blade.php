{{-- resources/views/components/stats-grid.blade.php --}}
@props(['config', 'stats'])

<div class="stats-section">
    <div class="stats-grid">
        <div class="stat-item">
            <span class="stat-number" style="color: {{ $config['color']['from'] }}">
                {{ $stats['total'] ?? 0 }}
            </span>
            <div class="stat-label">Total Antrian</div>
        </div>
        {{-- <div class="stat-item">
            <span class="stat-number" style="color: {{ $config['color']['from'] }}">
                {{ $stats['selesai'] ?? 0 }}
            </span>
            <div class="stat-label">Selesai</div>
        </div> --}}
        {{-- <div class="stat-item">
            <span class="stat-number" style="color: {{ $config['color']['from'] }}">
                {{ $stats['menunggu'] ?? 0 }}
            </span>
            <div class="stat-label">Menunggu</div>
        </div> --}}
        <div class="stat-item">
            <span class="stat-number" style="color: {{ $config['color']['from'] }}">
                {{ $stats['diproses'] ?? 0 }}
            </span>
            <div class="stat-label">Diproses</div>
        </div>
    </div>
</div>