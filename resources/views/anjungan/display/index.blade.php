<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - Display Antrian</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style media="screen">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            color: #fff;
            background: linear-gradient(135deg, #0264d6 0%, #1a4d8f 100%);
            height: 100vh;
            width: 100%;
            overflow: hidden;
        }
        
        .antrian_judul {
            font-size: 56px;
            padding: 20px 0;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        .logo-img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        
        .container-display {
            display: flex;
            height: calc(100vh - 140px);
            gap: 20px;
            padding: 20px;
        }
        
        .video-section {
            flex: 3;
            background: #000;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
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
            gap: 15px;
        }
        
        .queue-panel {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: 3px solid #28a745;
            transition: all 0.3s ease;
        }
        
        .queue-panel.loket {
            border-color: #28a745;
        }
        
        .queue-panel.cs {
            border-color: #007bff;
        }
        
        .queue-panel.apotek {
            border-color: #ffc107;
        }
        
        .queue-label {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .queue-number {
            font-size: 90px;
            font-weight: 300;
            line-height: 1;
            color: #0264d6;
            margin: 0;
        }
        
        .queue-panel.cs .queue-number {
            color: #007bff;
        }
        
        .queue-panel.apotek .queue-number {
            color: #ffc107;
        }
        
        .queue-counter {
            font-size: 24px;
            color: #666;
            margin-top: 15px;
            font-weight: 600;
        }
        
        .info-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            color: #333;
            font-weight: 600;
        }
        
        .info-item {
            font-size: 14px;
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-item i {
            width: 20px;
            color: #0264d6;
        }
        
        .date-display {
            font-size: 18px;
            color: #d9534f;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .clock-display {
            font-size: 32px;
            color: #0264d6;
            font-weight: 700;
        }
        
        .running-text-section {
            position: fixed;
            bottom: 50px;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 15px;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            z-index: 100;
            height: 50px;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        
        .running-text-content {
            white-space: nowrap;
            animation: scroll 30s linear infinite;
        }
        
        @keyframes scroll {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }
        
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            background: rgba(2, 100, 214, 0.95);
            color: #fff;
            font-size: 13px;
            padding: 12px 20px;
            z-index: 101;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 2px solid #fff;
        }
        
        footer a {
            color: #ffff00;
            text-decoration: none;
        }
        
        footer a:hover {
            text-decoration: underline;
        }
        
        .footer-left {
            flex: 2;
        }
        
        .footer-right {
            flex: 1;
            text-align: right;
        }
        
        @media (max-width: 1200px) {
            .container-display {
                flex-direction: column;
            }
            
            .video-section {
                flex: 1;
                height: 400px;
            }
            
            .queue-section {
                flex: 1;
                flex-direction: row;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="text-center">
        <h1 class="antrian_judul">
            @if($logo && file_exists(public_path($logo)))
                <img src="{{ asset($logo) }}" alt="Logo" class="logo-img">
            @else
                <i class="fa fa-hospital"></i>
            @endif
            Antrian Loket
        </h1>
    </div>
    
    <!-- Main Display -->
    <div class="container-display">
        <!-- Video Section (Left) -->
        <div class="video-section">
            @if($video_id)
                <iframe 
                    class="video-iframe"
                    src="https://www.youtube.com/embed/{{ $video_id }}?rel=0&modestbranding=1&autohide=1&mute=0&showinfo=0&controls=1&loop=1&autoplay=1&playlist={{ $video_id }}"
                    allowfullscreen
                    allow="autoplay">
                </iframe>
            @else
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #000;">
                    <div style="text-align: center;">
                        <i class="fa fa-video" style="font-size: 80px; color: #666; margin-bottom: 20px;"></i>
                        <p style="color: #999; font-size: 18px;">Video tidak dikonfigurasi</p>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Queue Section (Right) -->
        <div class="queue-section">
            <!-- Loket Panel -->
            <div class="queue-panel loket" id="panel-loket">
                <div class="queue-label">LOKET</div>
                <div class="queue-number">
                    A<span id="antrian-loket">-</span>
                </div>
                <div class="queue-counter">
                    Konter <span id="loket-loket">-</span>
                </div>
            </div>
            
            <!-- CS Panel -->
            <div class="queue-panel cs" id="panel-cs">
                <div class="queue-label">CS</div>
                <div class="queue-number">
                    B<span id="antrian-cs">-</span>
                </div>
                <div class="queue-counter">
                    Konter <span id="loket-cs">-</span>
                </div>
            </div>
            
            <!-- Apotek Panel -->
            <div class="queue-panel apotek" id="panel-apotek">
                <div class="queue-label">APOTEK</div>
                <div class="queue-number">
                    F<span id="antrian-apotek">-</span>
                </div>
                <div class="queue-counter">
                    Konter <span id="loket-apotek">-</span>
                </div>
            </div>
            
            <!-- Info Panel -->
            <div class="info-section">
                <div class="date-display" id="date-display">-</div>
                <div class="clock-display" id="clock-display">--:--:--</div>
            </div>
        </div>
    </div>
    
    <!-- Running Text -->
    <div class="running-text-section">
        <div class="running-text-content">
            {{ $running_text }}
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="footer-left">
            <i class="fa fa-calendar"></i> <span id="footer-date">{{ $tanggal }}</span>
            &nbsp;&nbsp;
            <i class="fa fa-clock"></i> <span id="footer-time">--:--:--</span>
            {{-- &nbsp;&nbsp;
            <i class="fa fa-user"></i> {{ $username }} --}}
        </div>
        <div class="footer-right">
            <span>Powered by <a href="https://github.com/basoro/antrian" target="_blank">Sistem Antrian</a></span>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <script>
    // ================================
    // CLOCK FUNCTION
    // ================================
    function updateClock() {
        const now = new Date();
        
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const timeString = `${hours}:${minutes}:${seconds}`;
        
        document.getElementById('clock-display').textContent = timeString;
        document.getElementById('footer-time').textContent = timeString;
    }
    
    // Update clock every second
    setInterval(updateClock, 1000);
    updateClock(); // Call once on load
    
    // ================================
    // DATE DISPLAY
    // ================================
    function updateDate() {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        const now = new Date();
        const day = days[now.getDay()];
        const date = now.getDate();
        const month = months[now.getMonth()];
        const year = now.getFullYear();
        
        const dateString = `${day}, ${date} ${month} ${year}`;
        document.getElementById('date-display').textContent = dateString;
    }
    
    updateDate();
    
    // ================================
    // GET ANTRIAN (Polling)
    // ================================
    function getAntrian() {
        $.ajax({
            url: '{{ route("anjungan.api.getdisplay") }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data.status == true) {
                    // Update display
                    updateDisplay(data.type, data.noantrian, data.loket);
                    
                    // Play audio
                    playAudioSequence(data.panggil);
                    
                    // Mark as selesai
                    setDisplaySelesai(data.id);
                    
                    // Set next polling
                    setTimeout(getAntrian, 5000);
                } else {
                    // Retry if no queue
                    setTimeout(getAntrian, 3000);
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.status);
                setTimeout(getAntrian, 3000);
            }
        });
    }
    
    // Start polling after 4 seconds
    setTimeout(getAntrian, 4000);
    
    // ================================
    // UPDATE DISPLAY
    // ================================
    function updateDisplay(type, nomor, loket) {
        const typeMap = {
            'Loket': { id: 'loket', prefix: 'A' },
            'CS': { id: 'cs', prefix: 'B' },
            'Apotek': { id: 'apotek', prefix: 'F' }
        };
        
        const typeInfo = typeMap[type];
        if (typeInfo) {
            document.getElementById('antrian-' + typeInfo.id).textContent = nomor;
            document.getElementById('loket-' + typeInfo.id).textContent = loket;
        }
    }
    
    // ================================
    // PLAY AUDIO SEQUENCE
    // ================================
    function playAudioSequence(audioFiles) {
        const baseUrl = '{{ asset("plugins/anjungan/suara") }}';
        const audioList = audioFiles.map(file => ({
            file: file,
            url: `${baseUrl}/${file}.wav`
        }));
        
        let index = 0;
        
        function playNext() {
            if (index >= audioList.length) {
                console.log('✅ Audio playback complete');
                return;
            }
            
            const audio = new Audio(audioList[index].url);
            audio.volume = 1.0;
            
            console.log(`🔊 Playing (${index + 1}/${audioList.length}): ${audioList[index].file}`);
            
            audio.play().catch(error => {
                console.error(`❌ Error playing ${audioList[index].file}:`, error);
                index++;
                setTimeout(playNext, 100);
            });
            
            audio.onended = function() {
                index++;
                setTimeout(playNext, 150);
            };
            
            audio.onerror = function() {
                console.error(`❌ File not found: ${audioList[index].file}`);
                index++;
                setTimeout(playNext, 100);
            };
        }
        
        playNext();
    }
    
    // ================================
    // SET DISPLAY SELESAI
    // ================================
    function setDisplaySelesai(id) {
        $.ajax({
            url: '{{ route("anjungan.api.setdisplayselesai") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: { id: id },
            dataType: 'json',
            error: function(xhr) {
                console.error('Error:', xhr.status);
            }
        });
    }
    </script>
</body>
</html>