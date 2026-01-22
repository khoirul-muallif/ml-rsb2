{{-- E:\laragon\www\ml-rsb2\resources\views\anjungan\loket\index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anjungan Mandiri - {{ $nama_instansi }}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .header {
            background: rgba(255,255,255,0.95);
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #667eea;
            margin: 10px 0 5px 0;
        }
        
        .header p {
            font-size: 16px;
            color: #666;
            margin: 0;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .welcome-text {
            text-align: center;
            color: #fff;
            margin-bottom: 40px;
        }
        
        .welcome-text h2 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .welcome-text p {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .loket-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
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
        
        /* Print Area */
        #printArea {
            display: none;
            text-align: center;
            padding: 40px;
            font-family: monospace;
        }
        
        @media print {
            body * { visibility: hidden; }
            #printArea, #printArea * { 
                visibility: visible; 
                display: block !important;
            }
            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
        
        .print-logo {
            width: 80px;
            margin-bottom: 20px;
        }
        
        .print-number {
            font-size: 72px;
            font-weight: 700;
            margin: 30px 0;
            letter-spacing: 5px;
        }
        
        .print-label {
            font-size: 24px;
            font-weight: 700;
            margin: 20px 0;
        }
        
        /* Loading Overlay */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .loading-content {
            text-align: center;
            color: #fff;
        }
        
        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #667eea;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Footer */
        footer {
            background: rgba(0,0,0,0.3);
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        @if($logo && file_exists(public_path($logo)))
            <img src="{{ asset($logo) }}" alt="Logo" style="height: 70px;">
        @endif
        <h1>{{ $nama_instansi }}</h1>
        <p>{{ $alamat }}</p>
    </div>
    
    <!-- Main Content -->
    <div class="main-container">
        <div class="welcome-text">
            <h2>Selamat Datang di Anjungan Mandiri</h2>
            <p>Silakan pilih layanan yang Anda butuhkan</p>
        </div>
        
        <div class="loket-grid">
            @foreach($loket_types as $key => $config)
                <div class="loket-card" 
                     onclick="ambilAntrian('{{ $config['type'] }}')"
                     style="--color-from: {{ $config['color']['from'] }}; --color-to: {{ $config['color']['to'] }}">
                    
                    @if(isset($config['badge']))
                        <div class="loket-badge">{{ $config['badge'] }}</div>
                    @endif
                    
                    <div class="loket-icon">
                        <i class="fa {{ $config['icon'] }}"></i>
                    </div>
                    
                    <div class="loket-label">{{ $config['label'] }}</div>
                    <div class="loket-desc">{{ $config['full_label'] }}</div>
                    
                    <div class="loket-stats">
                        <div class="stat-item">
                            <div class="stat-number" id="total-{{ $config['type'] }}">0</div>
                            <div class="stat-label">Total Hari Ini</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" id="menunggu-{{ $config['type'] }}">0</div>
                            <div class="stat-label">Menunggu</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <p>{{ $running_text }}</p>
        <p>&copy; {{ date('Y') }} {{ $nama_instansi }} - Powered by mLITE</p>
    </footer>
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <h3>Sedang memproses...</h3>
            <p>Mohon tunggu sebentar</p>
        </div>
    </div>
    
    <!-- Print Area -->
    <div id="printArea">
        @if($logo && file_exists(public_path($logo)))
            <img src="{{ asset($logo) }}" alt="Logo" class="print-logo">
        @endif
        <h2>{{ $nama_instansi }}</h2>
        <p>{{ $alamat }}</p>
        <hr>
        <div class="print-number" id="printNumber">-</div>
        <div class="print-label" id="printLabel">-</div>
        <hr>
        <p>{{ date('d-m-Y H:i') }}</p>
        <p style="margin-top: 20px; font-size: 14px;">
            Simpan struk ini sebagai bukti<br>
            Pantau nomor antrian Anda di layar display
        </p>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
    const CSRF_TOKEN = '{{ csrf_token() }}';
    
    // Ambil Antrian
    function ambilAntrian(type) {
        // Show loading
        document.getElementById('loadingOverlay').style.display = 'flex';
        
        fetch('/anjungan/loket/ambil', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ type: type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                // Set print data
                document.getElementById('printNumber').textContent = data.display;
                document.getElementById('printLabel').textContent = data.label;
                
                // Hide loading
                document.getElementById('loadingOverlay').style.display = 'none';
                
                // Print
                window.print();
                
                // Reload stats
                loadSummary();
            } else {
                alert('Gagal mengambil nomor antrian: ' + data.message);
                document.getElementById('loadingOverlay').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem');
            document.getElementById('loadingOverlay').style.display = 'none';
        });
    }
    
    // Load Summary Stats
    function loadSummary() {
        fetch('/anjungan/loket/summary')
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    Object.keys(data.summary).forEach(type => {
                        const stats = data.summary[type];
                        
                        const totalEl = document.getElementById('total-' + type);
                        const menungguEl = document.getElementById('menunggu-' + type);
                        
                        if (totalEl) totalEl.textContent = stats.total;
                        if (menungguEl) menungguEl.textContent = stats.menunggu;
                    });
                }
            })
            .catch(error => {
                console.error('Error loading summary:', error);
            });
    }
    
    // Load on page load
    loadSummary();
    
    // Auto refresh every 10 seconds
    setInterval(loadSummary, 10000);
    </script>
</body>
</html>