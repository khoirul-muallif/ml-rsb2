<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Pemanggil Antrian' }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .antrian_judul {
            font-size: 56px;
            padding-bottom: 20px;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .panel {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .panel:hover {
            transform: translateY(-5px);
        }
        
        .panel-body .no_antrian {
            font-size: 90px;
            font-weight: 300;
            padding: 0;
            margin: 0;
        }
        
        .panel-footer .no_loket {
            font-size: 40px;
            color: #000;
            padding-top: 0;
            padding-bottom: 0;
        }
        
        .btn-group-justified {
            display: flex;
            gap: 10px;
        }
        
        .btn-group-justified .btn {
            flex: 1;
        }
        
        .input-group-lg .form-control {
            font-size: 1.5rem;
        }
        
        .card-antrian {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .btn-custom {
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .instruction-list {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
        }
        
        .instruction-list li {
            font-size: 1.1rem;
            margin-bottom: 15px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="text-center mb-4">
            <h1 class="antrian_judul">Pemanggil Antrian</h1>
        </div>
        
        <!-- Panel Info Antrian -->
        <div class="row mb-4">
            <div class="col-md-4">
                <a href="{{ url('/anjungan/pemanggil?show=panggil_loket') }}" class="text-decoration-none">
                    <div class="panel border-success text-center">
                        <div class="panel-footer bg-transparent border-success py-3">
                            <div class="no_loket">Konter <span class="get_Loket">1</span></div>
                        </div>
                        <div class="panel-body text-success py-5">
                            <div class="no_antrian">A<span class="antrian_Loket">{{ $antrian ?? 0 }}</span></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ url('/anjungan/pemanggil?show=panggil_cs') }}" class="text-decoration-none">
                    <div class="panel border-primary text-center">
                        <div class="panel-footer bg-transparent border-primary py-3">
                            <div class="no_loket">Konter <span class="get_CS">2</span></div>
                        </div>
                        <div class="panel-body text-primary py-5">
                            <div class="no_antrian">B<span class="antrian_CS">{{ $antrian ?? 0 }}</span></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ url('/anjungan/pemanggil?show=panggil_apotek') }}" class="text-decoration-none">
                    <div class="panel border-warning text-center">
                        <div class="panel-footer bg-transparent border-warning py-3">
                            <div class="no_loket">Konter <span class="get_Apotek">3</span></div>
                        </div>
                        <div class="panel-body text-warning py-5">
                            <div class="no_antrian">F<span class="antrian_Apotek">{{ $antrian ?? 0 }}</span></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        @if(isset($show) && $show)
        <!-- Panel Pemanggil -->
        <div class="container text-center">
            <div class="row">
                <div class="card-deck">
                    @foreach($loket as $value)
                    <div class="{{ (isset($_GET['loket']) && $_GET['loket'] != $value) ? 'd-none' : '' }}">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-5">
                            <div class="panel">
                                <div class="panel-heading bg-primary text-white py-3" style="font-size:32px;">
                                    Loket {{ $value }}
                                </div>
                                <div class="panel-body py-5">
                                    <h5 class="panel-title" style="font-size:72px;">
                                        {{ strtoupper($namaloket) }}{{ $antrian }}
                                    </h5>
                                </div>
                                <div class="panel-footer p-4">
                                    <div class="btn-group btn-group-justified mb-3">
                                        <button class="btn btn-info btn-custom" style="font-size:32px;">
                                            {{ $antrian }}
                                        </button>
                                        <button class="btn btn-warning btn-custom" style="font-size:32px;" onclick="panggil({{ $antrian }})">
                                            <i class="fa fa-bullhorn"></i>
                                        </button>
                                        <a href="{{ url('/anjungan/pemanggil?show='.$panggil_loket.'&loket='.$value) }}" 
                                           class="btn btn-success btn-custom" style="font-size:32px;">
                                            <i class="fa fa-forward"></i>
                                        </a>
                                    </div>
                                    
                                    <div class="col" id="form_simpan_no_rkm_medis_{{ $value }}">
                                        <input type="hidden" name="noantrian" value="{{ $antrian }}">
                                        <div class="input-group input-group-lg">
                                            <input type="text" 
                                                   class="form-control form-control-lg" 
                                                   name="no_rkm_medis_{{ $value }}" 
                                                   placeholder="Input Nomor RM"
                                                   maxlength="6">
                                            <button type="button" class="btn btn-danger simpan_no_rkm_medis_{{ $value }}">
                                                Simpan
                                            </button>
                                        </div>
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
        <div class="text-center" style="width: 300px; margin: 20px auto;">
            <div class="card-antrian">
                <form action="">
                    <input type="hidden" name="show" value="{{ $panggil_loket }}">
                    <input type="hidden" name="reset" value="{{ $_GET['loket'] ?? '1' }}">
                    <div class="row">
                        <div class="col">
                            <div class="input-group input-group-lg">
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       name="antrian" 
                                       placeholder="Input Nomor Antrian"
                                       required>
                                <button class="btn btn-danger" type="submit">Panggil</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Instruksi Penggunaan -->
        <div class="container">
            <ul class="instruction-list">
                <li><i class="fa fa-forward text-success"></i> Klik tombol <i class="fa fa-forward"></i> 1x untuk memanggil antrian selanjutnya</li>
                <li><i class="fa fa-bullhorn text-warning"></i> Klik tombol <i class="fa fa-bullhorn"></i> 1x untuk memanggil ulang antrian</li>
                <li><i class="fa fa-keyboard text-primary"></i> Untuk menyesuaikan urutan, masukkan nomor urut pada kolom lompat dan klik tombol panggil 1x</li>
                <li><i class="fa fa-info-circle text-info"></i> Angka di Sebelah Kiri Tombol Pemanggil Menunjukan Jumlah Nomor Antrian Yang Telah diambil Pasien</li>
            </ul>
        </div>

        <!-- Audio Files -->
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
    // Setup CSRF token untuk AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Function panggil antrian dengan audio
    function panggil(noantrian) {
        var loket = '';
        @if(isset($_GET['loket']) && $_GET['loket'] !='')
        var loket = "{{ $_GET['loket'] }}";
        @endif
        
        // Putar suara
        playAntrianSequence(noantrian, loket);
        
        // Kirim ke server
        $.ajax({
            url: '{{ url("/anjungan/api/setpanggil") }}',
            type: 'POST',
            dataType: 'json',
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

    // Function untuk memutar suara secara berurutan
    function playAntrianSequence(noantrian, loket) {
        var playlist = [];
        var baseUrl = '{{ asset("plugins/anjungan/suara") }}';
        
        var tipe = '{{ str_replace("panggil_","", $panggil_loket ?? "") }}';
        
        // 1. Suara pembuka "antrian"
        playlist.push(baseUrl + '/antrian.wav');
        
        // 2. Huruf prefix
        if(tipe == 'loket') {
            playlist.push(baseUrl + '/a.wav');
        } else if(tipe == 'cs') {
            playlist.push(baseUrl + '/b.wav');
        } else if(tipe == 'apotek') {
            playlist.push(baseUrl + '/f.wav');
        }
        
        // 3. Nomor antrian
        convertToIndonesianAudio(noantrian, playlist, baseUrl);
        
        // 4. Suara "counter"
        if(loket) {
            playlist.push(baseUrl + '/counter.wav');
            // 5. Nomor loket
            convertToIndonesianAudio(loket, playlist, baseUrl);
        }
        
        console.log('🔊 Playlist:', playlist);
        playAudioSequence(playlist, 0);
    }

    // Convert angka ke suara bahasa Indonesia
    function convertToIndonesianAudio(number, playlist, baseUrl) {
        var num = parseInt(number);
        
        if(num == 0) {
            playlist.push(baseUrl + '/nol.wav');
            return;
        }
        
        // Ratusan
        if(num >= 200) {
            var ratusan = Math.floor(num / 100);
            playlist.push(baseUrl + '/' + ratusan + '.wav');
            playlist.push(baseUrl + '/ratus.wav');
            num = num % 100;
        } else if(num >= 100) {
            playlist.push(baseUrl + '/seratus.wav');
            num = num % 100;
        }
        
        // Puluhan
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
        
        // Satuan (1-9)
        if(num > 0) {
            playlist.push(baseUrl + '/' + num + '.wav');
        }
    }

    // Play audio sequence
    function playAudioSequence(playlist, index) {
        if (index >= playlist.length) {
            console.log('✅ Selesai memutar semua audio');
            return;
        }
        
        console.log('Playing (' + (index + 1) + '/' + playlist.length + '):', playlist[index]);
        
        var audio = new Audio(playlist[index]);
        audio.volume = 1.0;
        audio.preload = 'auto';
        
        audio.play().catch(function(error) {
            console.error('❌ Error playing:', playlist[index], error);
            setTimeout(function() {
                playAudioSequence(playlist, index + 1);
            }, 100);
        });
        
        audio.onended = function() {
            console.log('✓ Finished:', playlist[index]);
            setTimeout(function() {
                playAudioSequence(playlist, index + 1);
            }, 150);
        };
        
        audio.onerror = function() {
            console.error('❌ File not found:', playlist[index]);
            setTimeout(function() {
                playAudioSequence(playlist, index + 1);
            }, 100);
        };
    }

    // Simpan Nomor RM
    @foreach($loket ?? [] as $value)
    $("#form_simpan_no_rkm_medis_{{ $value }}").on("click", ".simpan_no_rkm_medis_{{ $value }}", function(event){
        var noantrian = $('input:hidden[name=noantrian]').val();
        var no_rkm_medis = $('input:text[name=no_rkm_medis_{{ $value }}]').val();
        var type = '{{ str_replace("panggil_","",$panggil_loket ?? "") }}';
        
        if(no_rkm_medis.length !== 6) {
            alert('Nomor RM harus 6 digit!');
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
            success: function(xhr) {
                if(xhr.status == true) {
                    alert(xhr.message);
                    $('input:text[name=no_rkm_medis_{{ $value }}]').val('');
                }
            },
            error: function(xhr) {
                alert('Simpan nomor RM gagal!!');
            }
        });
    });
    @endforeach

    // Auto play saat halaman load (jika ada parameter loket)
    document.addEventListener('DOMContentLoaded', function() {
        var antrianElement = document.querySelector('.panel-title');
        
        if(antrianElement) {
            var fullText = antrianElement.innerText.trim();
            var match = fullText.match(/([A-F])(\d+)/);
            
            if(match) {
                var noantrian = match[2];
                var params = new URLSearchParams(window.location.search);
                var loket = params.get('loket') || '1';
                
                if(typeof playAntrianSequence === 'function') {
                    playAntrianSequence(noantrian, loket);
                }
            }
        }
    });
    </script>
</body>
</html>