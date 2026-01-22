{{-- E:\laragon\www\ml-rsb2\resources\views\anjungan\display\single.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $config['full_label'] }} - Display Antrian</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            color: #fff;
            background: linear-gradient(135deg, {{ $config['color']['from'] }} 0%, {{ $config['color']['to'] }} 100%);
            height: 100vh;
            overflow: hidden;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .logo-img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        
        .header-title {
            color: #333;
        }
        
        .header-title h1 {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            color: {{ $config['color']['from'] }};
        }
        
        .header-title p {
            font-size: 18px;
            margin: 0;
            color: #666;
            font-weight: 600;
        }
        
        .header-right {
            text-align: right;
            color: #333;
        }
        
        .header-date {
            font-size: 16px;
            color: #d9534f;
            font-weight: 700;
        }
        
        .header-time {
            font-size: 28px;
            color: {{ $config['color']['from'] }};
            font-weight: 700;
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
        }
        
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
        
        @if(isset($config['badge']))
        .queue-display::before {
            content: "{{ $config['badge'] }}";
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
        @endif
        
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
            background: rgba({{ hexToRgb($config['color']['from']) }}, 0.1);
            border-radius: 10px;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: {{ $config['color']['from'] }};
            display: block;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
            font-weight: 600;
            margin-top: 5px;
        }
        
        .running-text {
            position: fixed;
            bottom: 50px;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            padding: 15px;
            overflow: hidden;
            height: 50px;
            display: flex;
            align-items: center;
        }
        
        .running-text-content {
            white-space: nowrap;
            animation: scroll 30s linear infinite;
            font-size: 20px;
            font-weight: 600;
        }
        
        @keyframes scroll {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            background: {{ $config['color']['from'] }};
            color: #fff;
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }
        
        footer a {
            color: #ffff00;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            @if($logo && file_exists(public_path($logo)))
                <img src="{{ asset($logo) }}" alt="Logo" class="logo-img">
            @else
                <i class="fa fa-hospital fa-3x" style="color: {{ $config['color']['from'] }}"></i>
            @endif
            <div class="header-title">
                <h1>{{ $config['full_label'] }}</h1>
                <p>{{ $nama_instansi }}</p>
            </div>
        </div>
        <div class="header-right">
            <div class="header-date" id="date-display">{{ $tanggal }}</div>
            <div class="header-time" id="time-display">--:--:--</div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-container">
        <!-- Video Section -->
        <div class="video-section">
            @if($video_id)
                <iframe 
                    class="video-iframe"
                    src="https://www.youtube.com/embed/{{ $video_id }}?rel=0&modestbranding=1&autohide=1&mute=0&showinfo=0&controls=1&loop=1&autoplay=1&playlist={{ $video_id }}"
                    allowfullscreen
                    allow="autoplay">
                </iframe>
            @else
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-video" style="font-size: 80px; color: #666;"></i>
                </div>
            @endif
        </div>
        
        <!-- Queue Display Section -->
        <div class="queue-section">
            <div class="queue-display">
                <div class="queue-label">
                    <i class="fa {{ $config['icon'] }}"></i> {{ $config['label'] }}
                </div>
                <div class="queue-number-display">
                    <span class="queue-prefix">{{ $config['prefix'] }}</span><span id="current-number">-</span>
                </div>
                <div class="queue-counter-display">
                    KONTER <span class="queue-counter-number" id="current-counter">-</span>
                </div>
            </div>
            
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
    <div class="running-text">
        <div class="running-text-content">{{ $running_text }}</div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div>© {{ date('Y') }} {{ $nama_instansi }}</div>
        <div>Powered by <a href="https://github.com/basoro/mlite" target="_blank">mLITE</a></div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
    const API_URL = '{{ route("anjungan.api.getdisplay") }}';
    const TYPE = '{{ $config["type"] }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    
    // Update Clock
    function updateClock() {
        const now = new Date();
        const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('time-display').textContent = time;
    }
    setInterval(updateClock, 1000);
    updateClock();
    
    // Get Antrian (Polling)
    function getAntrian() {
        $.ajax({
            url: API_URL + '?type=' + TYPE,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    updateDisplay(data);
                    playAudioSequence(data.panggil);
                    markAsSelesai(data.id);
                }
                setTimeout(getAntrian, 5000);
            },
            error: function() {
                setTimeout(getAntrian, 3000);
            }
        });
    }
    
    // Update Display
    function updateDisplay(data) {
        document.getElementById('current-number').textContent = data.noantrian;
        document.getElementById('current-counter').textContent = data.loket;
    }
    
    // Play Audio Sequence
    function playAudioSequence(audioFiles) {
        const baseUrl = '{{ asset("plugins/anjungan/suara") }}';
        let index = 0;
        
        function playNext() {
            if (index >= audioFiles.length) return;
            
            const audio = new Audio(`${baseUrl}/${audioFiles[index]}.wav`);
            audio.volume = 1.0;
            
            audio.play().catch(() => {
                index++;
                setTimeout(playNext, 100);
            });
            
            audio.onended = () => {
                index++;
                setTimeout(playNext, 150);
            };
        }
        
        playNext();
    }
    
    // Mark as Selesai
    function markAsSelesai(id) {
        $.ajax({
            url: '{{ route("anjungan.api.setdisplayselesai") }}',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
            data: { id: id }
        });
    }
    
    // Load Stats (Real-time)
    function loadStats() {
        $.ajax({
            url: '{{ route("anjungan.api.getstats") }}?type=' + TYPE,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.status && data.stats) {
                    document.getElementById('stat-total').textContent = data.stats.total;
                    document.getElementById('stat-selesai').textContent = data.stats.selesai;
                    document.getElementById('stat-menunggu').textContent = data.stats.menunggu;
                    document.getElementById('stat-diproses').textContent = data.stats.diproses;
                }
            },
            error: function() {
                console.error('Failed to load stats');
            }
        });
    }
    
    // Start
    setTimeout(getAntrian, 2000);
    setInterval(loadStats, 5000); // Update stats every 5 seconds
    loadStats();
    </script>
</body>
</html>

<?php
// Helper function for hex to RGB (jika belum ada)
function hexToRgb($hex) {
    $hex = str_replace('#', '', $hex);
    return hexdec(substr($hex, 0, 2)) . ', ' . 
           hexdec(substr($hex, 2, 2)) . ', ' . 
           hexdec(substr($hex, 4, 2));
}
?>