{{-- E:\laragon\www\ml-rsb2\resources\views\anjungan\display\index.blade.php --}}
@extends('layouts.app')

@section('title', $config['full_label'] . ' - Display Antrian')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/partials/global.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, {{ $config['color']['from'] }} 0%, {{ $config['color']['to'] }} 100%);
            min-height: 100vh;
            overflow: hidden;
            padding-bottom: 100px;
        }

        .display-header {
            position: sticky;
            top: 0;
            z-index: 100;
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

        .video-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .queue-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
            overflow-y: auto;
        }

        .queue-section > .queue-display {
            flex: 2;
            min-height: 0;
        }

        .queue-section > .stats-section {
            flex: 1;
            min-height: 0;
        }

        .stats-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            overflow-y: auto;
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

        .running-text-wrapper {
            position: fixed;
            bottom: 50px;
            left: 0;
            right: 0;
            z-index: 50;
        }

        .footer-wrapper {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 100;
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
            
            .queue-section > .queue-display {
                flex: auto;
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
                    class="video-iframe"
                    src="https://www.youtube.com/embed/{{ $video_id }}?rel=0&modestbranding=1&autohide=1&mute=0&showinfo=0&controls=1&loop=1&autoplay=1&playlist={{ $video_id }}"
                    allowfullscreen
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
            <!-- Queue Display -->
            <x-queue-display :config="$config" />

            <!-- Stats Section -->
            <div class="stats-section">
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-number" id="stat-total">0</span>
                        <div class="stat-label">Total Antrian</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="stat-selesai">0</span>
                        <div class="stat-label">Selesai</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="stat-menunggu">0</span>
                        <div class="stat-label">Menunggu</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="stat-diproses">0</span>
                        <div class="stat-label">Diproses</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Running Text -->
    <div class="running-text-wrapper">
        <x-running-text :text="$running_text" speed="30" />
    </div>

    <!-- Footer -->
    <div class="footer-wrapper">
        <x-footer :company="$nama_instansi" powered="mLITE" :year="true" />
    </div>

@push('scripts')
    <script src="{{ asset('js/partials/audio.js') }}"></script>
    <script src="{{ asset('js/partials/utils.js') }}"></script>
    <script>
    const API_URL = '{{ route("anjungan.api.getdisplay") }}';
    const STATS_URL = '{{ route("anjungan.api.getstats") }}';
    const TYPE = '{{ $config["type"] }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';

    // ✅ FIX: Tracking dengan timestamp untuk deteksi panggil ulang
    let lastPlayedKey = null;

    function getAntrian() {
        $.ajax({
            url: API_URL + '?type=' + TYPE,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data.status) {
                    // Update tampilan
                    document.getElementById('current-number').textContent = data.noantrian;
                    document.getElementById('current-counter').textContent = data.loket;
                    
                    // ✅ FIX: Gunakan timestamp untuk deteksi perubahan
                    // Key format: "nomor-loket-timestamp"
                    // Jadi jika petugas klik "Panggil" lagi, timestamp berubah → audio play
                    const currentKey = data.noantrian + '-' + data.loket + '-' + data.timestamp;
                    
                    if(currentKey !== lastPlayedKey) {
                        console.log('🔊 Panggil terdeteksi:', {
                            nomor: data.noantrian,
                            loket: data.loket,
                            timestamp: data.timestamp,
                            reason: lastPlayedKey ? 'Nomor baru atau panggil ulang' : 'Pertama kali'
                        });
                        
                        playAntrianSequence(data.noantrian, data.loket, data.prefix.toLowerCase());
                        
                        // Update tracking
                        lastPlayedKey = currentKey;
                        
                        // Mark as selesai
                        markAsSelesai(data.id);
                    } else {
                        console.log('⏭️ Nomor sama, skip audio:', currentKey);
                    }
                }
                
                // Polling lagi setelah 5 detik
                setTimeout(getAntrian, 5000);
            },
            error: function(xhr) {
                console.error('❌ Error getting antrian:', xhr);
                // Retry setelah 3 detik jika error
                setTimeout(getAntrian, 3000);
            }
        });
    }

    function getStats() {
        $.ajax({
            url: STATS_URL + '?type=' + TYPE,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data.status && data.stats) {
                    document.getElementById('stat-total').textContent = data.stats.total || '0';
                    document.getElementById('stat-selesai').textContent = data.stats.selesai || '0';
                    document.getElementById('stat-menunggu').textContent = data.stats.menunggu || '0';
                    document.getElementById('stat-diproses').textContent = data.stats.diproses || '0';
                }
            },
            error: function(xhr) {
                console.error('❌ Error getting stats:', xhr);
            }
        });
    }

    function markAsSelesai(id) {
        $.ajax({
            url: '{{ route("anjungan.api.setdisplayselesai") }}',
            type: 'POST',
            headers: { 
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({ id: id }),
            error: function(xhr) {
                console.error('❌ Error marking as selesai:', xhr);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('📺 Display initialized for type:', TYPE);
        
        // Start polling setelah 2 detik
        setTimeout(getAntrian, 2000);
        
        // Update stats setiap 5 detik
        setInterval(getStats, 5000);
        getStats();
    });
    </script>
@endpush

@endsection