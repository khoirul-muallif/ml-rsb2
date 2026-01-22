@extends('layouts.app')

@section('title', $config['full_label'] . ' - Display Antrian')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/partials/global.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, {{ $config['color']['from'] }} 0%, {{ $config['color']['to'] }} 100%);
            height: 100vh;
            overflow: hidden;
        }

        .main-container {
            display: flex;
            height: calc(100vh - 200px);
            gap: 30px;
            padding: 30px;
        }

        .video-section {
            flex: 2;
            background: #000;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        .queue-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .stats-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: rgba(0,0,0,0.02);
            border-radius: 10px;
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            display: block;
            color: {{ $config['color']['from'] }};
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            font-weight: 600;
            margin-top: 5px;
        }

        @media (max-width: 1024px) {
            .main-container {
                flex-direction: column;
                height: auto;
                gap: 20px;
                padding: 20px;
            }

            .video-section {
                flex: 1;
                min-height: 300px;
            }

            .queue-section {
                flex: 1;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Header -->
    <x-display-header 
        :config="$config"
        :logo="$logo ?? null"
        :company="$nama_instansi"
        :date="$tanggal"
    />

    <!-- Main Container -->
    <div class="main-container">
        <!-- Video -->
        <div class="video-section">
            @if($video_id)
                <iframe 
                    style="width:100%;height:100%;border:none"
                    src="https://www.youtube.com/embed/{{ $video_id }}?rel=0&autoplay=1&loop=1&playlist={{ $video_id }}"
                    allow="autoplay">
                </iframe>
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                    <i class="fa fa-video" style="font-size:80px;color:#666"></i>
                </div>
            @endif
        </div>

        <!-- Queue Display & Stats -->
        <div class="queue-section">
            <x-queue-display 
                :config="$config"
                :antrian="$antrian ?? '-'"
                :counter="$counter ?? '-'"
                :stats="$stats ?? null"
            />

            <div class="stats-section">
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-number" id="stat-total">{{ $stats['total'] ?? 0 }}</span>
                        <div class="stat-label">Total Antrian</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="stat-selesai">{{ $stats['selesai'] ?? 0 }}</span>
                        <div class="stat-label">Selesai</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="stat-menunggu">{{ $stats['menunggu'] ?? 0 }}</span>
                        <div class="stat-label">Menunggu</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="stat-diproses">{{ $stats['diproses'] ?? 0 }}</span>
                        <div class="stat-label">Diproses</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Running Text -->
    <x-running-text :text="$running_text" speed="30" />

    <!-- Footer -->
    <x-footer :company="$nama_instansi" powered="mLITE" :year="true" />

@push('scripts')
    <script src="{{ asset('js/partials/audio.js') }}"></script>
    <script src="{{ asset('js/partials/utils.js') }}"></script>
    <script>
    const API_URL = '{{ route("anjungan.api.getdisplay") }}';
    const TYPE = '{{ $config["type"] }}';

    // Polling untuk update antrian
    function getAntrian() {
        $.ajax({
            url: API_URL + '?type=' + TYPE,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data.status) {
                    document.getElementById('current-number').textContent = data.noantrian;
                    document.getElementById('current-counter').textContent = data.loket;
                    
                    playAntrianSequence(data.noantrian, data.loket);
                    markAsSelesai(data.id);
                }
                setTimeout(getAntrian, 5000);
            },
            error: function() {
                setTimeout(getAntrian, 3000);
            }
        });
    }

    // Polling untuk update stats
    function getStats() {
        $.ajax({
            url: '{{ route("anjungan.api.getstats") }}?type=' + TYPE,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data.status && data.stats) {
                    document.getElementById('stat-total').textContent = data.stats.total;
                    document.getElementById('stat-selesai').textContent = data.stats.selesai;
                    document.getElementById('stat-menunggu').textContent = data.stats.menunggu;
                    document.getElementById('stat-diproses').textContent = data.stats.diproses;
                }
            }
        });
    }

    function markAsSelesai(id) {
        $.ajax({
            url: '{{ route("anjungan.api.setdisplayselesai") }}',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: { id: id }
        });
    }

    // Start
    setTimeout(getAntrian, 2000);
    setInterval(getStats, 5000);
    getStats();
    </script>
@endpush

@endsection