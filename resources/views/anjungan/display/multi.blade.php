{{-- E:\laragon\www\ml-rsb2\resources\views\anjungan\display\multi.blade.php --}}
@extends('layouts.app')

@section('title', 'Display Antrian - Semua Loket')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/partials/global.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            overflow: hidden;
        }

        .display-multi-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            padding: 20px;
            height: calc(100vh - 200px);
        }

        .display-card-wrapper {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            border: 4px solid;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .card-number {
            font-size: 80px;
            font-weight: 300;
            line-height: 1;
            margin: 20px 0;
        }

        .card-counter {
            font-size: 18px;
            color: #666;
            font-weight: 600;
        }

        .card-prefix {
            font-weight: 700;
        }
    </style>
@endpush

@section('content')
    <!-- Header -->
    <x-header 
        :logo="$logo ?? null"
        :title="$nama_instansi"
        :showTime="true"
        :showDate="true"
    />

    <!-- Multi Display Grid -->
    <div class="display-multi-container">
        @foreach($all_configs as $type => $config)
            <div class="display-card-wrapper" 
                 style="border-color: {{ $config['color']['from'] ?? '#667eea' }}"
                 data-type="{{ $type }}"
                 data-audio-name="{{ strtolower($config['audio_name'] ?? $config['prefix']) }}">
                <div class="card-title" style="color: {{ $config['color']['from'] ?? '#667eea' }}">
                    <i class="fa {{ $config['icon'] ?? 'fa-door-open' }}"></i> {{ $config['label'] }}
                </div>
                
                <div class="card-number" style="color: {{ $config['color']['from'] ?? '#667eea' }}">
                    <span class="card-prefix">{{ $config['prefix'] ?? 'A' }}</span><span id="num-{{ $type }}">-</span>
                </div>
                
                <div class="card-counter">
                    Konter <span id="counter-{{ $type }}">-</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Running Text -->
    <x-running-text :text="$running_text" speed="30" />

    <!-- Footer -->
    <x-footer :company="$nama_instansi" powered="mLITE" :year="true" />

@push('scripts')
    <script src="{{ asset('js/partials/audio.js') }}"></script>
    <script src="{{ asset('js/partials/utils.js') }}"></script>
    <script>
    // ✅ FIX: Tracking nomor terakhir per loket
    const lastPlayed = {
        'Loket': null,
        'LoketVIP': null,
        'CS': null,
        'CSVIP': null,
        'Apotek': null
    };

    /**
     * ✅ FIX: Polling dengan pengecekan perubahan nomor
     */
    function pollAllDisplays() {
        const types = ['Loket', 'LoketVIP', 'CS', 'CSVIP', 'Apotek'];
        
        types.forEach(type => {
            $.ajax({
                url: '/anjungan/api/getdisplay?type=' + type,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if(data.status) {
                        // Update tampilan
                        document.getElementById('num-' + type).textContent = data.noantrian;
                        document.getElementById('counter-' + type).textContent = data.loket;
                        
                        // ✅ FIX: Hanya putar audio jika nomor BERUBAH
                        const currentKey = data.noantrian + '-' + data.loket;
                        
                        if(lastPlayed[type] !== currentKey) {
                            console.log('🔊 [' + type + '] Nomor baru:', currentKey, '(sebelumnya:', lastPlayed[type], ')');
                            
                            // Ambil audio_name dari card
                            const card = document.querySelector('[data-type="' + type + '"]');
                            const audioName = card ? card.dataset.audioName : 'a';
                            
                            playAntrianSequence(data.noantrian, data.loket, audioName);
                            
                            // Update tracking
                            lastPlayed[type] = currentKey;
                        } else {
                            console.log('⏭️ [' + type + '] Nomor sama, skip audio:', currentKey);
                        }
                    }
                },
                error: function(xhr) {
                    // Ignore errors untuk multi display (agar tidak spam console)
                    if(xhr.status !== 404) {
                        console.error('❌ [' + type + '] Error:', xhr.status);
                    }
                }
            });
        });
    }

    // Start polling
    document.addEventListener('DOMContentLoaded', function() {
        console.log('📺 Multi-display initialized');
        
        // First poll after 2 seconds
        setTimeout(pollAllDisplays, 2000);
        
        // Then poll every 5 seconds
        setInterval(pollAllDisplays, 5000);
    });
    </script>
@endpush

@endsection