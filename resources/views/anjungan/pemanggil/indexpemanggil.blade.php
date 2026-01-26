{{-- E:\laragon\www\ml-rsb2\resources\views\anjungan\pemanggil\index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $config['full_label'] ?? 'Pemanggil Antrian' }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, {{ $config['color']['from'] }} 0%, {{ $config['color']['to'] }} 100%);
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
            margin-bottom: 30px;
        }
        
        .panel:hover {
            transform: translateY(-5px);
        }
        
        .panel-heading {
            background: linear-gradient(135deg, {{ $config['color']['from'] }}, {{ $config['color']['to'] }});
            color: {{ $config['color']['text'] }};
            padding: 20px;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
        }
        
        .panel-body {
            padding: 40px;
            text-align: center;
        }
        
        .panel-body .no_antrian {
            font-size: 90px;
            font-weight: 300;
            color: {{ $config['color']['from'] }};
            margin: 0;
        }
        
        .panel-footer {
            padding: 30px;
            background: #f8f9fa;
        }
        
        .btn-group-justified {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .btn-group-justified .btn {
            flex: 1;
            font-size: 32px;
            padding: 15px;
        }
        
        .input-group-lg .form-control {
            font-size: 1.5rem;
        }
        
        .card-antrian {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 20px auto;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            max-width: 500px;
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
        
        .badge-vip {
            background: linear-gradient(135deg, #ffd700, #ff9800);
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        {{-- Header --}}
        <div class="text-center mb-4">
            <h1 class="antrian_judul">
                <i class="fas {{ $config['icon'] }}"></i>
                {{ $config['full_label'] }}
                @if(isset($config['badge']))
                    <span class="badge-vip">{{ $config['badge'] }}</span>
                @endif
            </h1>
        </div>
        
        {{-- Panel Pemanggil per Loket --}}
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    @foreach($loket as $value)
                    {{-- ✅ FIXED: Tampilkan hanya loket yang dipilih --}}
                    @if(!isset($current_loket) || $current_loket == $value)
                    <div class="panel">
                        {{-- Header Panel --}}
                        <div class="panel-heading">
                            <i class="fas {{ $config['icon'] }}"></i> Loket {{ $value }}
                        </div>
                        
                        {{-- Current Queue Display --}}
                        <div class="panel-body">
                            <h2 class="no_antrian">
                                {{ $namaloket }}{{ $antrian }}
                            </h2>
                            <p class="text-muted mt-3">Nomor Antrian Saat Ini</p>
                        </div>
                        
                        {{-- Controls --}}
                        <div class="panel-footer">
                            {{-- Tombol Kontrol --}}
                            <div class="btn-group-justified">
                                <button class="btn btn-info" style="font-size:32px;">
                                    {{ $antrian }}
                                </button>
                                <button class="btn btn-warning" style="font-size:32px;" onclick="panggil({{ $antrian }})">
                                    <i class="fa fa-bullhorn"></i> Panggil
                                </button>
                                <a href="{{ url('/anjungan/pemanggil?show='.$panggil_loket.'&loket='.$value) }}" 
                                   class="btn btn-success" style="font-size:32px;">
                                    <i class="fa fa-forward"></i> Next
                                </a>
                            </div>
                            
                            {{-- Form Input Nomor RM --}}
                            {{-- <div id="form_simpan_no_rkm_medis_{{ $value }}">
                                <input type="hidden" name="noantrian" value="{{ $antrian }}">
                                <div class="input-group input-group-lg">
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           name="no_rkm_medis_{{ $value }}" 
                                           placeholder="Input Nomor RM (6 digit)"
                                           maxlength="6">
                                    <button type="button" class="btn btn-danger btn-lg simpan_no_rkm_medis_{{ $value }}">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Form Lompat Antrian --}}
        <div class="card-antrian">
            <h5 class="text-center mb-3">Lompat ke Nomor Antrian</h5>
            <form action="{{ url('/anjungan/pemanggil') }}" method="GET" id="formLompatAntrian">
                <input type="hidden" name="show" value="{{ $panggil_loket }}">
                <input type="hidden" name="loket" value="{{ $current_loket ?? '1' }}">
                <input type="hidden" name="skip_audio" value="1">
                <div class="input-group input-group-lg">
                    <input type="number" 
                           class="form-control form-control-lg" 
                           name="antrian" 
                           id="inputLompatAntrian"
                           placeholder="Nomor Antrian"
                           min="1"
                           @if($noantrian > 0)
                           max="{{ $noantrian }}"
                           @endif
                           required>
                    <button class="btn btn-danger btn-lg" type="submit">
                        <i class="fas fa-bullhorn"></i> Panggil
                    </button>
                </div>
                @if($noantrian > 0)
                <small class="text-muted d-block mt-2 text-center">
                    Total antrian hari ini: <strong>{{ $noantrian }}</strong>
                </small>
                @else
                <small class="text-warning d-block mt-2 text-center">
                    <i class="fas fa-exclamation-triangle"></i> Belum ada antrian hari ini
                </small>
                @endif
            </form>
        </div>

        {{-- Instruksi Penggunaan --}}
        <div class="container">
            <ul class="instruction-list">
                <li>
                    <i class="fa fa-forward text-success"></i> 
                    Klik tombol <strong>Next</strong> untuk memanggil antrian selanjutnya
                </li>
                <li>
                    <i class="fa fa-bullhorn text-warning"></i> 
                    Klik tombol <strong>Panggil</strong> untuk memanggil ulang antrian saat ini
                </li>
                <li>
                    <i class="fa fa-keyboard text-primary"></i> 
                    Untuk melompat ke nomor tertentu, gunakan form "Lompat ke Nomor Antrian"
                </li>
                <li>
                    <i class="fa fa-save text-danger"></i> 
                    Input Nomor RM pasien setelah memanggil antrian
                </li>
                <li>
                    <i class="fa fa-info-circle text-info"></i> 
                    Total antrian hari ini: <strong>{{ $noantrian }}</strong>
                </li>
            </ul>
        </div>

        {{-- Hidden Audio Tags --}}
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
    // Setup CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ✅ FIXED: Function panggil dengan type yang benar
    function panggil(noantrian) {
        var loket = '{{ $current_loket ?? "1" }}';
        var type = '{{ str_replace("panggil_", "", $panggil_loket ?? "") }}';
        
        console.log('🔊 Memanggil antrian:', {
            noantrian: noantrian,
            type: type,
            loket: loket
        });
        
        // Putar suara
        //playAntrianSequence(noantrian, loket, type);
        
        // Kirim ke server
        $.ajax({
            url: '{{ url("/anjungan/api/setpanggil") }}',
            type: 'POST',
            dataType: 'json',
            data: {
                noantrian: noantrian,
                type: type,
                loket: loket,
            },
            success: function(data) {
                console.log('✅ Panggil berhasil:', data);
            },
            error: function(xhr) {
                console.error('❌ Panggil gagal:', xhr);
                alert('Gagal memanggil antrian. Silakan coba lagi.');
            }
        });
    }

    // Function untuk memutar suara secara berurutan
    function playAntrianSequence(noantrian, loket, type) {
        var playlist = [];
        var baseUrl = '{{ asset("plugins/anjungan/suara") }}';
        
        // 1. Suara pembuka "antrian"
        playlist.push(baseUrl + '/antrian.wav');
        
        // 2. Huruf prefix (berdasarkan audio_name di config)
        var audioName = '{{ $config["audio_name"] ?? "a" }}';
        playlist.push(baseUrl + '/' + audioName + '.wav');
        
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
        
        console.log('🔊 Playing (' + (index + 1) + '/' + playlist.length + '):', playlist[index]);
        
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
        var type = '{{ str_replace("panggil_","", $panggil_loket ?? "") }}';
        
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
                alert('Simpan nomor RM gagal!');
            }
        });
    });
    @endforeach

    // Auto play saat halaman load dengan parameter loket
    document.addEventListener('DOMContentLoaded', function() {
        var params = new URLSearchParams(window.location.search);
        var loket = params.get('loket');
        var skipAudio = params.get('skip_audio'); // ✅ NEW: Check skip flag
        
        // ✅ FIXED: Auto-play HANYA jika dari tombol Next, TIDAK dari form lompat
        if(loket && !params.get('antrian') && !skipAudio) {
            var antrian = {{ $antrian ?? 0 }};
            if(antrian > 0) {
                setTimeout(function() {
                    panggil(antrian);
                }, 500);
            }
        }
    });

    // ✅ NEW: Validation untuk form lompat antrian
    document.getElementById('formLompatAntrian')?.addEventListener('submit', function(e) {
        var input = document.getElementById('inputLompatAntrian');
        var nilai = parseInt(input.value);
        var max = parseInt(input.getAttribute('max') || '999');
        
        if (nilai < 1) {
            e.preventDefault();
            alert('Nomor antrian minimal 1');
            return false;
        }
        
        if (max > 0 && nilai > max) {
            e.preventDefault();
            alert('Nomor antrian maksimal ' + max + ' (total antrian hari ini)');
            return false;
        }
        
        // ✅ Confirm sebelum lompat
        if (!confirm('Yakin ingin memanggil antrian nomor ' + nilai + '?')) {
            e.preventDefault();
            return false;
        }
    });
    </script>
</body>
</html>