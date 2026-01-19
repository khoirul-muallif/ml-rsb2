<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Pemanggil Antrian CS' }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            min-height: 100vh;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .antrian_judul {
            font-size: 56px;
            padding-bottom: 20px;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            font-weight: 700;
        }
        
        .panel {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
            transition: transform 0.3s ease;
            border: 3px solid #007bff !important;
        }
        
        .panel:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        
        .panel-body {
            padding: 40px 20px !important;
        }
        
        .panel-body .no_antrian {
            font-size: 90px;
            font-weight: 300;
            padding: 0;
            margin: 0;
            line-height: 1;
            color: #007bff;
        }
        
        .panel-footer {
            padding: 20px !important;
        }
        
        .panel-footer .no_loket {
            font-size: 40px;
            color: #000;
            padding: 0;
            margin: 0;
            font-weight: 600;
        }
        
        .btn-group-justified {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .btn-group-justified .btn {
            flex: 1;
            padding: 12px !important;
            font-size: 1.5rem !important;
        }
        
        .btn-custom {
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .btn-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .input-group-lg .form-control {
            font-size: 1.25rem;
            padding: 15px;
        }
        
        .input-group-lg .btn {
            padding: 15px 25px;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .card-antrian {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin: 20px 0;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border: none;
        }
        
        .instruction-list {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .instruction-list li {
            font-size: 1.1rem;
            margin-bottom: 15px;
            color: #333;
            font-weight: 500;
        }
        
        .instruction-list i {
            margin-right: 10px;
            width: 25px;
        }
        
        .panel-heading {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
            color: white !important;
            padding: 15px !important;
            border: none !important;
            font-weight: 600;
            font-size: 24px !important;
        }
        
        .panel-title {
            font-size: 72px;
            font-weight: 300;
            margin: 0;
            padding: 0;
            line-height: 1;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="antrian_judul">
                <i class="fa fa-headset"></i> {{ $title ?? 'Pemanggil Antrian CS' }}
            </h1>
        </div>
        
        <!-- Navigation Back -->
        <div class="text-center mb-4">
            <a href="{{ url('/anjungan/pemanggil') }}" class="btn btn-light btn-sm">
                <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
        
        <!-- Panel Pemanggil Antrian -->
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    @foreach($loket as $value)
                    <div class="mb-5">
                        <div class="panel">
                            <!-- Header Loket -->
                            <div class="panel-heading">
                                <i class="fa fa-phone"></i> Loket {{ $value }}
                            </div>
                            
                            <!-- Display Nomor Antrian -->
                            <div class="panel-body text-center">
                                <h5 class="panel-title">
                                    {{ strtoupper($namaloket) }}<span class="antrian-number">{{ $antrian }}</span>
                                </h5>
                            </div>
                            
                            <!-- Control Buttons & Forms -->
                            <div class="panel-footer">
                                <!-- Button Group -->
                                <div class="btn-group-justified mb-3">
                                    <button type="button" class="btn btn-info btn-custom" disabled style="font-size:28px; font-weight: 700;">
                                        {{ $antrian }}
                                    </button>
                                    <button type="button" class="btn btn-warning btn-custom" onclick="panggil({{ $antrian }}, '{{ $value }}')" title="Panggil ulang antrian">
                                        <i class="fa fa-bullhorn"></i> Panggil
                                    </button>
                                    <a href="{{ url('/anjungan/pemanggil?show='.$panggil_loket.'&loket='.$value) }}" 
                                       class="btn btn-primary btn-custom" title="Panggil antrian berikutnya">
                                        <i class="fa fa-forward"></i> Berikutnya
                                    </a>
                                </div>

                                <!-- Form: Input Nomor RM -->
                                <div id="form_simpan_no_rkm_medis_{{ $value }}">
                                    <input type="hidden" name="noantrian" value="{{ $antrian }}">
                                    <div class="input-group input-group-lg">
                                        <input type="text" 
                                               class="form-control" 
                                               name="no_rkm_medis_{{ $value }}" 
                                               placeholder="Input Nomor RM (6 digit)"
                                               maxlength="6"
                                               inputmode="numeric"
                                               pattern="[0-9]{6}">
                                        <button type="button" class="btn btn-danger simpan_no_rkm_medis_{{ $value }}">
                                            <i class="fa fa-save"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Form Lompat Antrian -->
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card-antrian">
                        <h6 class="mb-3" style="color: #666; font-weight: 600;">
                            <i class="fa fa-arrow-right text-primary"></i> Lompat ke Nomor Tertentu
                        </h6>
                        <form action="{{ url('/anjungan/pemanggil') }}" method="GET" id="formLompatAntrian">
                            @csrf
                            <input type="hidden" name="show" value="{{ $panggil_loket }}">
                            <input type="hidden" name="loket" value="{{ request('loket', '1') }}">
                            
                            <div class="input-group input-group-lg">
                                <input type="text" 
                                       class="form-control" 
                                       name="antrian" 
                                       placeholder="Nomor Antrian"
                                       inputmode="numeric"
                                       pattern="[0-9]*"
                                       required>
                                <button class="btn btn-primary" type="submit" style="font-weight: 600;">
                                    <i class="fa fa-check"></i> Panggil
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">Masukkan nomor untuk loncat ke antrian tertentu</small>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instruksi Penggunaan -->
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <ul class="instruction-list">
                        <li>
                            <i class="fa fa-forward text-primary fa-lg"></i>
                            <strong>Tombol Berikutnya (→):</strong> Klik untuk memanggil antrian selanjutnya
                        </li>
                        <li>
                            <i class="fa fa-bullhorn text-warning fa-lg"></i>
                            <strong>Tombol Panggil (🔔):</strong> Klik untuk memanggil ulang antrian yang sama
                        </li>
                        <li>
                            <i class="fa fa-keyboard text-primary fa-lg"></i>
                            <strong>Form Lompat:</strong> Masukkan nomor untuk loncat ke antrian tertentu
                        </li>
                        <li>
                            <i class="fa fa-barcode text-info fa-lg"></i>
                            <strong>Input RM:</strong> Masukkan nomor rekam medis 6 digit dan klik Simpan
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Audio Files (Hidden) -->
        @if(isset($xcounter))
        <div style="display: none;">
            @foreach($xcounter as $audio)
                {!! $audio !!}
            @endforeach
        </div>
        @endif
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function panggil(noantrian, loket) {
        // Jika loket tidak dikirim, ambil dari URL parameter
        if (!loket) {
            var params = new URLSearchParams(window.location.search);
            loket = params.get('loket') || '1';
        }
        
        console.log('🔔 Panggil - noantrian:', noantrian, 'loket:', loket);
        playAntrianSequence(noantrian, loket);
        
        $.ajax({
            url: '{{ url("/anjungan/api/setpanggil") }}',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                noantrian: noantrian,
                type: '{{ str_replace("panggil_","", $panggil_loket ?? "") }}',
                loket: loket,
            },
            success: function(data) {
                console.log('✅ Panggil berhasil:', data);
            },
            error: function(xhr) {
                console.error('❌ Panggil gagal:', xhr.status);
            }
        });
    }

    function convertToIndonesianAudio(number, playlist, baseUrl) {
        var num = parseInt(number);
        
        if(num == 0) {
            playlist.push(baseUrl + '/nol.wav');
            return;
        }
        
        if(num >= 200) {
            var ratusan = Math.floor(num / 100);
            playlist.push(baseUrl + '/' + ratusan + '.wav');
            playlist.push(baseUrl + '/ratus.wav');
            num = num % 100;
        } else if(num >= 100) {
            playlist.push(baseUrl + '/seratus.wav');
            num = num % 100;
        }
        
        if(num >= 20) {
            var puluhan = Math.floor(num / 10);
            playlist.push(baseUrl + '/' + puluhan + '.wav');
            playlist.push(baseUrl + '/puluh.wav');
            num = num % 10;
        } else if(num >= 11) {
            if(num == 11) {
                playlist.push(baseUrl + '/sebelas.wav');
            } else {
                var satuan = num - 10;
                playlist.push(baseUrl + '/' + satuan + '.wav');
                playlist.push(baseUrl + '/belas.wav');
            }
            return;
        } else if(num == 10) {
            playlist.push(baseUrl + '/sepuluh.wav');
            return;
        }
        
        if(num > 0) {
            playlist.push(baseUrl + '/' + num + '.wav');
        }
    }

    function playAudioSequence(playlist, index) {
        if (index >= playlist.length) {
            console.log('✅ Selesai memutar audio');
            return;
        }
        
        var audio = new Audio(playlist[index]);
        audio.volume = 1.0;
        audio.preload = 'auto';
        
        audio.play().catch(function(error) {
            console.error('❌ Error:', playlist[index], error);
            setTimeout(function() {
                playAudioSequence(playlist, index + 1);
            }, 100);
        });
        
        audio.onended = function() {
            setTimeout(function() {
                playAudioSequence(playlist, index + 1);
            }, 150);
        };
        
        audio.onerror = function() {
            setTimeout(function() {
                playAudioSequence(playlist, index + 1);
            }, 100);
        };
    }

    function playAntrianSequence(noantrian, loket) {
        var playlist = [];
        var baseUrl = '{{ asset("plugins/anjungan/suara") }}';
        var tipe = '{{ str_replace("panggil_","", $panggil_loket ?? "") }}';
        
        playlist.push(baseUrl + '/antrian.wav');
        
        if(tipe == 'cs') {
            playlist.push(baseUrl + '/b.wav');
        }
        
        convertToIndonesianAudio(noantrian, playlist, baseUrl);
        
        if(loket) {
            playlist.push(baseUrl + '/counter.wav');
            convertToIndonesianAudio(loket, playlist, baseUrl);
        }
        
        playAudioSequence(playlist, 0);
    }

    @foreach($loket ?? [] as $value)
    $("#form_simpan_no_rkm_medis_{{ $value }}").on("click", ".simpan_no_rkm_medis_{{ $value }}", function(event){
        event.preventDefault();
        
        var noantrian = $('#form_simpan_no_rkm_medis_{{ $value }} input[name=noantrian]').val();
        var no_rkm_medis = $('#form_simpan_no_rkm_medis_{{ $value }} input[name=no_rkm_medis_{{ $value }}]').val();
        var type = '{{ str_replace("panggil_","",$panggil_loket ?? "") }}';
        
        if(no_rkm_medis.length !== 6) {
            alert('⚠️ Nomor RM harus 6 digit!');
            return;
        }
        
        $.ajax({
            url: '{{ url("/anjungan/api/simpannorm") }}',
            type: 'POST',
            dataType: 'json',
            data: { 
                noantrian: noantrian, 
                type: type, 
                no_rkm_medis: no_rkm_medis 
            },
            success: function(response) {
                if(response.status == true) {
                    alert('✅ ' + response.message);
                    $('#form_simpan_no_rkm_medis_{{ $value }} input[name=no_rkm_medis_{{ $value }}]').val('');
                } else {
                    alert('❌ ' + response.message);
                }
            },
            error: function(xhr) {
                alert('❌ Gagal menyimpan nomor RM!');
            }
        });
    });
    @endforeach

    document.addEventListener('DOMContentLoaded', function() {
        var antrianElement = document.querySelector('.panel-title');
        
        if(antrianElement) {
            var fullText = antrianElement.innerText.trim();
            var match = fullText.match(/([A-F])(\d+)/);
            
            if(match) {
                var noantrian = match[2];
                var params = new URLSearchParams(window.location.search);
                var loket = params.get('loket') || '1';
                
                setTimeout(function() {
                    playAntrianSequence(noantrian, loket);
                }, 1000);
            }
        }
    });
    </script>
</body>
</html>