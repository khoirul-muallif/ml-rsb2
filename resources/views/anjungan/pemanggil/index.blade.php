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
        
        <h2>
            <i class="fas {{ $config['icon'] }}"></i>
            {{ $config['full_label'] }}
            @if(isset($config['badge']))
                <span class="vip-badge">{{ $config['badge'] }}</span>
            @endif
        </h2>
    </div>

    <!-- Queue Panels -->
    @foreach($loket as $value)
        @if(!isset($current_loket) || $current_loket == $value)
            <x-queue-panel 
                :config="$config" 
                :loket="$value" 
                :antrian="$antrian"
                :panggil_loket="$panggil_loket"
                data-queue-type="{{ $config['type'] }}">
                <x-queue-controls 
                    :antrian="$antrian" 
                    :loket="$value"
                    :config="$config"
                    :panggil_loket="$panggil_loket"
                    :max_antrian="$noantrian" />
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
            ['icon' => 'fa-forward', 'title' => 'Berikutnya (→)', 'text' => 'Panggil antrian selanjutnya secara otomatis'],
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
    const CURRENT_TYPE = '{{ str_replace("panggil_", "", $panggil_loket ?? "") }}';

    /**
     * Panggil antrian (untuk tombol "Panggil" saja)
     * Fungsi ini HANYA dipanggil dari tombol Panggil, BUKAN dari Next/Lompat
     */
    function panggil(noantrian, loket = '1', audioName = 'a') {
        console.log('🔊 Manual Panggil:', { noantrian, loket, audioName });
        
        // Play audio sequence
        playAntrianSequence(noantrian, loket, audioName);
        
        // Update database (set status = 1)
        $.ajax({
            url: '{{ route("anjungan.api.setpanggil") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data: {
                noantrian: noantrian,
                type: CURRENT_TYPE,
                loket: loket,
            },
            success: function(data) {
                console.log('✅ Set panggil berhasil:', data);
            },
            error: function(xhr) {
                console.error('❌ Set panggil gagal:', xhr);
                if(typeof showToast === 'function') {
                    showToast('Gagal memanggil antrian', 'error');
                }
            }
        });
    }

    // ❌ REMOVED: DOMContentLoaded auto-play
    // Sekarang audio HANYA main saat klik tombol "Panggil"
    // Tombol "Berikutnya" dan "Lompat" TIDAK auto-play audio
    </script>
@endpush

@endsection