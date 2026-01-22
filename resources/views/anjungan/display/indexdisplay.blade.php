{{-- E:\laragon\www\ml-rsb2\resources\views\anjungan\display\index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Display Antrian - {{ $nama_instansi }}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            color: #fff;
            background: linear-gradient(135deg, #0264d6 0%, #1a4d8f 100%);
            height: 100vh;
            overflow: hidden;
        }
        
        .header {
            background: rgba(255,255,255,0.95);
            padding: 15px 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #0264d6;
            margin: 0;
        }
        
        .header-time {
            font-size: 24px;
            color: #0264d6;
            font-weight: 700;
        }
        
        .main-container {
            display: flex;
            height: calc(100vh - 150px);
            gap: 20px;
            padding: 20px;
        }
        
        .video-section {
            flex: 2.5;
            background: #000;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .queue-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .queue-panel {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .queue-panel.loket {
            border-left: 8px solid #28a745;
        }
        
        .queue-panel.cs {
            border-left: 8px solid #007bff;
        }
        
        .queue-panel.apotek {
            border-left: 8px solid #ffc107;
        }
        
        .queue-label {
            font-size: 16px;
            font-weight: 700;
            color: #666;
            margin-bottom: 8px;
        }
        
        .queue-number {
            font-size: 70px;
            font-weight: 300;
            line-height: 1;
            color: #28a745;
        }
        
        .queue-panel.cs .queue-number { color: #007bff; }
        .queue-panel.apotek .queue-number { color: #ffc107; }
        
        .queue-counter {
            font-size: 18px;
            color: #666;
            margin-top: 10px;
            font-weight: 600;
        }
        
        .running-text {
            position: fixed;
            bottom: 50px;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.8);
            padding: 12px;
            overflow: hidden;
            height: 50px;
        }
        
        .running-text-content {
            white-space: nowrap;
            animation: scroll 30s linear infinite;
            font-size: 18px;
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
            background: #0264d6;
            color: #fff;
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fa fa-hospital"></i> Display Antrian - {{ $nama_instansi }}</h1>
        <div class="header-time" id="time-display">--:--:--</div>
    </div>
    
    <div class="main-container">
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
        
        <div class="queue-section">
            <div class="queue-panel loket">
                <div class="queue-label">LOKET</div>
                <div class="queue-number">A<span id="num-loket">-</span></div>
                <div class="queue-counter">Konter <span id="counter-loket">-</span></div>
            </div>
            
            <div class="queue-panel cs">
                <div class="queue-label">CUSTOMER SERVICE</div>
                <div class="queue-number">B<span id="num-cs">-</span></div>
                <div class="queue-counter">Konter <span id="counter-cs">-</span></div>
            </div>
            
            <div class="queue-panel apotek">
                <div class="queue-label">APOTEK</div>
                <div class="queue-number">F<span id="num-apotek">-</span></div>
                <div class="queue-counter">Konter <span id="counter-apotek">-</span></div>
            </div>
        </div>
    </div>
    
    <div class="running-text">
        <div class="running-text-content">{{ $running_text }}</div>
    </div>
    
    <footer>
        <div>{{ $tanggal }}</div>
        <div>Powered by mLITE</div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
    function updateClock() {
        const now = new Date();
        document.getElementById('time-display').textContent = 
            now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    setInterval(updateClock, 1000);
    updateClock();
    
    function getAntrian() {
        $.get('{{ route("anjungan.api.getdisplay") }}', function(data) {
            if (data.status) {
                const typeMap = {
                    'Loket': 'loket',
                    'LoketVIP': 'loket',
                    'CS': 'cs',
                    'CSVIP': 'cs',
                    'Apotek': 'apotek'
                };
                
                const target = typeMap[data.type];
                if (target) {
                    document.getElementById('num-' + target).textContent = data.noantrian;
                    document.getElementById('counter-' + target).textContent = data.loket;
                    
                    playAudio(data.panggil);
                    
                    $.post('{{ route("anjungan.api.setdisplayselesai") }}', {
                        _token: '{{ csrf_token() }}',
                        id: data.id
                    });
                }
            }
            setTimeout(getAntrian, 5000);
        }).fail(function() {
            setTimeout(getAntrian, 3000);
        });
    }
    
    function playAudio(files) {
        const base = '{{ asset("plugins/anjungan/suara") }}';
        let i = 0;
        
        function next() {
            if (i >= files.length) return;
            const audio = new Audio(`${base}/${files[i]}.wav`);
            audio.play().catch(() => { i++; setTimeout(next, 100); });
            audio.onended = () => { i++; setTimeout(next, 150); };
        }
        
        next();
    }
    
    setTimeout(getAntrian, 2000);
    </script>
</body>
</html>