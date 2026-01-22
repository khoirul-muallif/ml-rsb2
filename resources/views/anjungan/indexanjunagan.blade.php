{{-- E:\laragon\www\ml-rsb2\resources\views\anjungan\index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Antrian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .dashboard-header {
            text-align: center;
            color: #fff;
            margin-bottom: 50px;
        }
        .dashboard-header h1 {
            font-size: 56px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 10px;
        }
        .dashboard-header p {
            font-size: 20px;
            opacity: 0.9;
        }
        .loket-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        .loket-card {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: block;
            position: relative;
            overflow: hidden;
        }
        .loket-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        .loket-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--color-from), var(--color-to));
        }
        .loket-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #fff;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--color-from), var(--color-to));
        }
        .loket-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 10px;
        }
        .loket-subtitle {
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .badge-vip {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #ffd700, #ff9800);
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .display-card {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: #fff;
        }
        .display-card .loket-icon {
            background: rgba(255,255,255,0.2);
        }
        .display-card .loket-title,
        .display-card .loket-subtitle {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="dashboard-header">
            <h1><i class="fas fa-hospital"></i> Sistem Antrian</h1>
            <p>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY • HH:mm') }}</p>
        </div>

        {{-- Loket Grid --}}
        <div class="loket-grid">
            {{-- Loket Reguler --}}
            <a href="{{ url('/anjungan/pemanggil?show=panggil_loket') }}" 
               class="loket-card"
               style="--color-from: #28a745; --color-to: #20c997;">
                <div class="loket-icon">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="loket-title">LOKET</div>
                <div class="loket-subtitle">Loket Pendaftaran Reguler</div>
            </a>

            {{-- Loket VIP --}}
            <a href="{{ url('/anjungan/pemanggil?show=panggil_loket_vip') }}" 
               class="loket-card"
               style="--color-from: #ffd700; --color-to: #ff9800;">
                <span class="badge-vip">EXECUTIVE</span>
                <div class="loket-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="loket-title">LOKET VIP</div>
                <div class="loket-subtitle">Loket Pendaftaran VIP</div>
            </a>

            {{-- CS Reguler --}}
            <a href="{{ url('/anjungan/pemanggil?show=panggil_cs') }}" 
               class="loket-card"
               style="--color-from: #007bff; --color-to: #0056b3;">
                <div class="loket-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="loket-title">CS</div>
                <div class="loket-subtitle">Customer Service Reguler</div>
            </a>

            {{-- CS VIP --}}
            <a href="{{ url('/anjungan/pemanggil?show=panggil_cs_vip') }}" 
               class="loket-card"
               style="--color-from: #6f42c1; --color-to: #5a32a3;">
                <span class="badge-vip">EXECUTIVE</span>
                <div class="loket-icon">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <div class="loket-title">CS VIP</div>
                <div class="loket-subtitle">Customer Service VIP</div>
            </a>

            {{-- Apotek --}}
            <a href="{{ url('/anjungan/pemanggil?show=panggil_apotek') }}" 
               class="loket-card"
               style="--color-from: #ffc107; --color-to: #ff9800;">
                <div class="loket-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <div class="loket-title">APOTEK</div>
                <div class="loket-subtitle">Farmasi Rumah Sakit</div>
            </a>

            {{-- Display Monitor --}}
            <a href="{{ url('/anjungan/display') }}" 
               class="loket-card display-card">
                <div class="loket-icon">
                    <i class="fas fa-tv"></i>
                </div>
                <div class="loket-title">MONITOR DISPLAY</div>
                <div class="loket-subtitle">Tampilan untuk TV / Monitor Ruang Tunggu</div>
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>