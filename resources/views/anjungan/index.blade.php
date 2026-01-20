<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Antrian - Sistem Pemanggil</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .page-header {
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }
        
        .page-header h1 {
            font-size: 56px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 15px;
        }
        
        .page-header p {
            font-size: 18px;
            opacity: 0.95;
        }
        
        .loket-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .loket-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            flex-direction: column;
        }
        
        .loket-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            text-decoration: none;
            color: inherit;
        }
        
        .loket-header {
            padding: 25px;
            color: white;
            flex-shrink: 0;
        }
        
        .loket-header h3 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .loket-header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .loket-body {
            padding: 30px 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: #f8f9fa;
        }
        
        .nomor-antrian {
            font-size: 80px;
            font-weight: 300;
            line-height: 1;
            margin: 0;
            color: #333;
        }
        
        .konter-info {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .loket-footer {
            padding: 15px 25px;
            border-top: 1px solid #dee2e6;
            display: flex;
            gap: 10px;
        }
        
        .btn-loket {
            flex: 1;
            padding: 10px 15px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            text-align: center;
        }
        
        .btn-loket:hover {
            transform: scale(1.02);
            text-decoration: none;
            color: white;
        }
        
        .btn-pemanggil {
            background: #667eea;
        }
        
        .btn-pemanggil:hover {
            background: #5568d3;
        }
        
        .btn-display {
            background: #764ba2;
        }
        
        .btn-display:hover {
            background: #654089;
        }
        
        /* Loket Colors */
        .loket-card.loket .loket-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .loket-card.cs .loket-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        
        .loket-card.apotek .loket-header {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        }
        
        .loket-card.igd .loket-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        
        .loket-card.laboratorium .loket-header {
            background: linear-gradient(135deg, #e83e8c 0%, #bd2130 100%);
        }
        
        .loket-card.radiologi .loket-header {
            background: linear-gradient(135deg, #17a2b8 0%, #0c5460 100%);
        }
        
        .info-box {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        
        .info-box h3 {
            margin-bottom: 20px;
            color: #333;
            font-weight: 700;
        }
        
        .info-box p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 15px;
        }
        
        .info-box p:last-child {
            margin-bottom: 0;
        }
        
        .icon-box {
            display: inline-block;
            width: 50px;
            height: 50px;
            background: #667eea;
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <h1><i class="fa fa-hospital"></i> Sistem Antrian</h1>
            <p>Dashboard Monitoring & Pemanggil Antrian Loket</p>
        </div>
        
        <!-- Info Box -->
        <div class="info-box">
            <h3><i class="fa fa-info-circle"></i> Pilih Layanan</h3>
            <p>
                Klik kartu di bawah untuk mengakses sistem pemanggil atau display antrian. 
                Setiap loket memiliki sistem antrian terpisah dengan nomor unik.
            </p>
        </div>
        
        <!-- Loket Grid -->
        <div class="loket-grid">
            <!-- LOKET A -->
            <div class="loket-card loket">
                <div class="loket-header">
                    <h3><i class="fa fa-door-open"></i> LOKET</h3>
                    <p>Layanan Umum</p>
                </div>
                <div class="loket-body">
                    <h5 class="nomor-antrian">A-</h5>
                    <div class="konter-info">Status Siap</div>
                </div>
                <div class="loket-footer">
                    <a href="{{ url('/anjungan/pemanggil?show=panggil_loket') }}" class="btn-loket btn-pemanggil">
                        <i class="fa fa-bullhorn"></i> Panggil
                    </a>
                    <a href="{{ url('/anjungan/display?type=Loket') }}" class="btn-loket btn-display">
                        <i class="fa fa-tv"></i> Display
                    </a>
                </div>
            </div>
            
            <!-- CS -->
            <div class="loket-card cs">
                <div class="loket-header">
                    <h3><i class="fa fa-headset"></i> CS</h3>
                    <p>Customer Service</p>
                </div>
                <div class="loket-body">
                    <h5 class="nomor-antrian">B-</h5>
                    <div class="konter-info">Status Siap</div>
                </div>
                <div class="loket-footer">
                    <a href="{{ url('/anjungan/pemanggil?show=panggil_cs') }}" class="btn-loket btn-pemanggil">
                        <i class="fa fa-bullhorn"></i> Panggil
                    </a>
                    <a href="{{ url('/anjungan/display?type=CS') }}" class="btn-loket btn-display">
                        <i class="fa fa-tv"></i> Display
                    </a>
                </div>
            </div>
            
            <!-- APOTEK -->
            <div class="loket-card apotek">
                <div class="loket-header">
                    <h3><i class="fa fa-pills"></i> APOTEK</h3>
                    <p>Layanan Farmasi</p>
                </div>
                <div class="loket-body">
                    <h5 class="nomor-antrian">F-</h5>
                    <div class="konter-info">Status Siap</div>
                </div>
                <div class="loket-footer">
                    <a href="{{ url('/anjungan/pemanggil?show=panggil_apotek') }}" class="btn-loket btn-pemanggil">
                        <i class="fa fa-bullhorn"></i> Panggil
                    </a>
                    <a href="{{ url('/anjungan/display?type=Apotek') }}" class="btn-loket btn-display">
                        <i class="fa fa-tv"></i> Display
                    </a>
                </div>
            </div>
            
            <!-- IGD -->
            <div class="loket-card igd">
                <div class="loket-header">
                    <h3><i class="fa fa-ambulance"></i> IGD</h3>
                    <p>Instalasi Gawat Darurat</p>
                </div>
                <div class="loket-body">
                    <h5 class="nomor-antrian">C-</h5>
                    <div class="konter-info">Status Siap</div>
                </div>
                <div class="loket-footer">
                    <a href="{{ url('/anjungan/pemanggil?show=panggil_igd') }}" class="btn-loket btn-pemanggil">
                        <i class="fa fa-bullhorn"></i> Panggil
                    </a>
                    <a href="{{ url('/anjungan/display?type=IGD') }}" class="btn-loket btn-display">
                        <i class="fa fa-tv"></i> Display
                    </a>
                </div>
            </div>
            
            <!-- LABORATORIUM -->
            <div class="loket-card laboratorium">
                <div class="loket-header">
                    <h3><i class="fa fa-flask"></i> LAB</h3>
                    <p>Laboratorium</p>
                </div>
                <div class="loket-body">
                    <h5 class="nomor-antrian">L-</h5>
                    <div class="konter-info">Status Siap</div>
                </div>
                <div class="loket-footer">
                    <a href="{{ url('/anjungan/pemanggil?show=panggil_laboratorium') }}" class="btn-loket btn-pemanggil">
                        <i class="fa fa-bullhorn"></i> Panggil
                    </a>
                    <a href="{{ url('/anjungan/display?type=Laboratorium') }}" class="btn-loket btn-display">
                        <i class="fa fa-tv"></i> Display
                    </a>
                </div>
            </div>
            
            <!-- RADIOLOGI -->
            <div class="loket-card radiologi">
                <div class="loket-header">
                    <h3><i class="fa fa-x-ray"></i> RADIOLOGI</h3>
                    <p>Instalasi Radiologi</p>
                </div>
                <div class="loket-body">
                    <h5 class="nomor-antrian">R-</h5>
                    <div class="konter-info">Status Siap</div>
                </div>
                <div class="loket-footer">
                    <a href="{{ url('/anjungan/pemanggil?show=panggil_radiologi') }}" class="btn-loket btn-pemanggil">
                        <i class="fa fa-bullhorn"></i> Panggil
                    </a>
                    <a href="{{ url('/anjungan/display?type=Radiologi') }}" class="btn-loket btn-display">
                        <i class="fa fa-tv"></i> Display
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Footer Info -->
        <div class="info-box" style="background: rgba(255,255,255,0.9); border-top: 3px solid #667eea;">
            <p style="margin: 0; text-align: center; font-size: 14px;">
                <i class="fa fa-copyright"></i> Sistem Antrian Terintegrasi | 
                <i class="fa fa-mobile"></i> Responsive & Cloud Ready | 
                <i class="fa fa-server"></i> Real-time Monitoring
            </p>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</body>
</html>