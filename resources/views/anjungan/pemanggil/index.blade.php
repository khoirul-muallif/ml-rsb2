{{-- E:\laragon\www\ml-rsb2\resources\views\anjungan\pemanggil\index.blade.php --}}
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

        /* ✅ NEW: Styling untuk alert konfirmasi */
        .swal2-popup {
            font-size: 1.2rem !important;
        }
        
        .swal2-title {
            font-size: 1.8rem !important;
        }
        
        .swal2-html-container {
            font-size: 1.4rem !important;
        }
        
        .swal2-confirm, .swal2-cancel {
            font-size: 1.2rem !important;
            padding: 12px 30px !important;
        }
    </style>
@endpush

@section('content')
<div class="container-pemanggil">
    <!-- Header -->
    <div class="antrian-header">
        <a href="{{ url('/anjungan/pemanggil/menu') }}" class="back-btn">
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
            ['icon' => 'fa-forward', 'title' => 'Berikutnya (→)', 'text' => 'Panggil antrian selanjutnya (tanpa audio di halaman ini)'],
            ['icon' => 'fa-bullhorn', 'title' => 'Panggil (🔔)', 'text' => 'Panggil ulang + putar audio preview'],
            ['icon' => 'fa-keyboard', 'title' => 'Lompat', 'text' => 'Masukkan nomor untuk loncat ke antrian tertentu'],
            ['icon' => 'fa-tv', 'title' => 'Display', 'text' => 'Audio akan otomatis terdengar di layar display TV/monitor'],
            ['icon' => 'fa-info-circle', 'title' => 'Total', 'text' => 'Antrian hari ini: ' . $noantrian]
        ]" />
</div>

@push('scripts')
    {{-- <script src="{{ asset('js/partials/audio.js') }}"></script> --}}
    <script src="{{ asset('js/partials/utils.js') }}"></script>
    <!-- ✅ NEW: SweetAlert2 untuk konfirmasi -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const CURRENT_TYPE = '{{ str_replace("panggil_", "", $panggil_loket ?? "") }}';
    const AUDIO_NAME = '{{ strtolower($config["audio_name"] ?? $config["prefix"]) }}';
    const CURRENT_LOKET = '{{ $current_loket ?? "1" }}';

    /**
     * ✅ NEW: Panggil dengan konfirmasi terlebih dahulu
     */
    function panggil(noantrian) {
        const displayNumber = '{{ $config["prefix"] }}' + noantrian;
        
        Swal.fire({
            title: 'Konfirmasi Panggil',
            html: `
                <div style="font-size: 1.4rem; margin: 20px 0;">
                    Panggil antrian <strong style="color: {{ $config['color']['from'] }}; font-size: 2rem;">${displayNumber}</strong> 
                    ke Loket <strong>${CURRENT_LOKET}</strong>?
                </div>
                <div style="font-size: 1.1rem; color: #666; margin-top: 15px;">
                    <i class="fa fa-info-circle"></i> Audio preview akan diputar di sini<br>
                    <i class="fa fa-tv"></i> Audio utama akan terdengar di layar display
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fa fa-bullhorn"></i> Ya, Panggil!',
            cancelButtonText: '<i class="fa fa-times"></i> Batal',
            confirmButtonColor: '{{ $config["color"]["from"] }}',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
            focusCancel: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                // ✅ User konfirmasi → Jalankan panggil
                executePanggil(noantrian);
            }
        });
    }

    /**
     * ✅ NEW: Execute panggil setelah konfirmasi
     */
    function executePanggil(noantrian) {
        console.log('🔊 Panggil dikonfirmasi:', { noantrian, loket: CURRENT_LOKET, audioName: AUDIO_NAME });
        
        // 1. Play audio preview (HANYA untuk tombol "Panggil")
        //playAntrianSequence(noantrian, CURRENT_LOKET, AUDIO_NAME);
        
        // 2. Update database (ini akan trigger display untuk play audio juga)
        $.ajax({
            url: '{{ route("anjungan.api.setpanggil") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data: {
                noantrian: noantrian,
                type: CURRENT_TYPE,
                loket: CURRENT_LOKET,
            },
            success: function(data) {
                console.log('✅ Set panggil berhasil:', data);
                
                // ✅ Show success notification
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: `Antrian <strong>{{ $config["prefix"] }}${noantrian}</strong> sudah dipanggil ke Loket ${CURRENT_LOKET}`,
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            },
            error: function(xhr) {
                console.error('❌ Set panggil gagal:', xhr);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tidak dapat memanggil antrian. Silakan coba lagi.',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }

    /**
     * ✅ Form validation untuk lompat antrian
     */
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🎛️ Pemanggil initialized:', {
            type: CURRENT_TYPE,
            loket: CURRENT_LOKET,
            audioName: AUDIO_NAME
        });

        // Validation untuk form lompat
        const formLompat = document.getElementById('formLompat');
        if(formLompat) {
            formLompat.addEventListener('submit', function(e) {
                const input = this.querySelector('input[name="antrian"]');
                const nilai = parseInt(input.value);
                const max = parseInt(input.getAttribute('max') || '999');
                
                if(nilai < 1) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Input Tidak Valid',
                        text: 'Nomor antrian minimal 1',
                        confirmButtonColor: '{{ $config["color"]["from"] }}'
                    });
                    return false;
                }
                
                if(max > 0 && nilai > max) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Input Tidak Valid',
                        text: `Nomor antrian maksimal ${max} (total antrian hari ini)`,
                        confirmButtonColor: '{{ $config["color"]["from"] }}'
                    });
                    return false;
                }
                
                // Konfirmasi lompat
                e.preventDefault();
                Swal.fire({
                    title: 'Lompat ke Antrian',
                    html: `Yakin ingin memanggil antrian <strong style="color: {{ $config['color']['from'] }}">{{ $config["prefix"] }}${nilai}</strong>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lompat!',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '{{ $config["color"]["from"] }}',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        formLompat.submit();
                    }
                });
            });
        }
    });
    </script>
@endpush

@endsection