@extends('layouts.app')

@section('title', $config['full_label'] ?? 'Pemanggil Antrian')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/partials/global.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, {{ $config['color']['from'] }} 0%, {{ $config['color']['to'] }} 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .antrian-header {
            text-align: center;
            color: #fff;
            margin-bottom: 40px;
            padding: 40px 20px;
        }

        .antrian-header h1 {
            font-size: 48px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin: 0 0 15px 0;
        }

        .back-btn {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .vip-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 700;
            margin-left: 10px;
            box-shadow: 0 2px 8px rgba(255, 215, 0, 0.4);
        }

        .container-pemanggil {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
        }
    </style>
@endpush

@section('content')
<div class="container-pemanggil">
    <!-- Header -->
    <div class="antrian-header">
        <a href="{{ url('/anjungan') }}" class="back-btn">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
        
        <h1>
            <i class="fas {{ $config['icon'] }}"></i>
            {{ $config['full_label'] }}
            @if(isset($config['badge']))
                <span class="vip-badge">{{ $config['badge'] }}</span>
            @endif
        </h1>
    </div>

    <!-- Queue Panels -->
    @foreach($loket as $value)
        @if(!isset($current_loket) || $current_loket == $value)
            <x-queue-panel 
                :config="$config" 
                :loket="$value" 
                :antrian="$antrian"
                :panggil_loket="$panggil_loket">
                <x-queue-controls 
                    :antrian="$antrian" 
                    :loket="$value"
                    :config="$config"
                    :panggil_loket="$panggil_loket" />
            </x-queue-panel>
        @endif
    @endforeach

    <!-- Jump Form -->
    <x-jump-form 
        :panggil_loket="$panggil_loket" 
        :max_antrian="$noantrian"
        :current_loket="$current_loket ?? 1" />

    <!-- Instructions -->
    <x-instructions 
        title="Panduan Penggunaan"
        :items="[
            ['icon' => 'fa-forward', 'title' => 'Berikutnya (→)', 'text' => 'Panggil antrian selanjutnya'],
            ['icon' => 'fa-bullhorn', 'title' => 'Panggil (🔔)', 'text' => 'Panggil ulang antrian yang sama'],
            ['icon' => 'fa-keyboard', 'title' => 'Lompat', 'text' => 'Masukkan nomor untuk loncat ke antrian tertentu'],
            ['icon' => 'fa-info-circle', 'title' => 'Total', 'text' => 'Antrian hari ini: ' . $noantrian]
        ]" />
</div>

@push('scripts')
    <script src="{{ asset('js/partials/audio.js') }}"></script>
    <script src="{{ asset('js/partials/utils.js') }}"></script>
    <script>
    const CSRF_TOKEN = '{{ csrf_token() }}';

    function panggil(noantrian, loket = '1', audioName = 'a') {
        console.log('🔊 Panggil:', { noantrian, loket, audioName });
        playAntrianSequence(noantrian, loket, audioName);
        
        $.ajax({
            url: '/anjungan/api/setpanggil',
            type: 'POST',
            dataType: 'json',
            data: {
                noantrian: noantrian,
                type: '{{ str_replace("panggil_", "", $panggil_loket ?? "") }}',
                loket: loket,
            },
            success: function(data) {
                console.log('✅ Panggil berhasil');
            },
            error: function(xhr) {
                console.error('❌ Panggil gagal');
                showToast('Gagal memanggil antrian', 'error');
            }
        });
    }

    // Auto-play on next button
    document.addEventListener('DOMContentLoaded', function() {
        var params = new URLSearchParams(window.location.search);
        if(params.get('loket') && !params.get('skip_audio')) {
            var antrian = {{ $antrian ?? 0 }};
            if(antrian > 0) {
                setTimeout(() => {
                    panggil(antrian, params.get('loket'), '{{ $config['audio_name'] ?? 'a' }}');
                }, 500);
            }
        }
    });
    </script>
@endpush

@endsection