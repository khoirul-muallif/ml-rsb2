<?php

return [
    // ==========================================
    // LOKET CONFIGURATION
    // Refactored from hard-coded blade files
    // ==========================================
    
    'loket' => [
        // REGULER SERVICES
        'Loket' => [
            'type' => 'Loket',
            'prefix' => 'A',
            'label' => 'LOKET',
            'full_label' => 'LOKET PENDAFTARAN',
            'category' => 'reguler',
            'color' => [
                'from' => '#28a745',
                'to' => '#20c997',
                'text' => '#ffffff'
            ],
            'icon' => 'fa-door-open',
            'show' => 'panggil_loket',
            'audio_name' => 'a', // untuk suara "antrian A..."
            'order' => 1
        ],
        
        'CS' => [
            'type' => 'CS',
            'prefix' => 'B',
            'label' => 'CS',
            'full_label' => 'CUSTOMER SERVICE',
            'category' => 'reguler',
            'color' => [
                'from' => '#007bff',
                'to' => '#0056b3',
                'text' => '#ffffff'
            ],
            'icon' => 'fa-headset',
            'show' => 'panggil_cs',
            'audio_name' => 'b',
            'order' => 2
        ],
        
        'Apotek' => [
            'type' => 'Apotek',
            'prefix' => 'F',
            'label' => 'APOTEK',
            'full_label' => 'APOTEK',
            'category' => 'reguler',
            'color' => [
                'from' => '#ffc107',
                'to' => '#ff9800',
                'text' => '#000000'
            ],
            'icon' => 'fa-pills',
            'show' => 'panggil_apotek',
            'audio_name' => 'f',
            'order' => 3
        ],
        
        'IGD' => [
            'type' => 'IGD',
            'prefix' => 'C',
            'label' => 'IGD',
            'full_label' => 'INSTALASI GAWAT DARURAT',
            'category' => 'reguler',
            'color' => [
                'from' => '#dc3545',
                'to' => '#c82333',
                'text' => '#ffffff'
            ],
            'icon' => 'fa-ambulance',
            'show' => 'panggil_igd',
            'audio_name' => 'c',
            'order' => 4
        ],
        
        'Laboratorium' => [
            'type' => 'Laboratorium',
            'prefix' => 'L',
            'label' => 'LAB',
            'full_label' => 'LABORATORIUM',
            'category' => 'reguler',
            'color' => [
                'from' => '#e83e8c',
                'to' => '#c21760',
                'text' => '#ffffff'
            ],
            'icon' => 'fa-flask',
            'show' => 'panggil_lab',
            'audio_name' => 'l',
            'order' => 5
        ],
        
        'Radiologi' => [
            'type' => 'Radiologi',
            'prefix' => 'R',
            'label' => 'RADIOLOGI',
            'full_label' => 'RADIOLOGI',
            'category' => 'reguler',
            'color' => [
                'from' => '#17a2b8',
                'to' => '#117a8b',
                'text' => '#ffffff'
            ],
            'icon' => 'fa-x-ray',
            'show' => 'panggil_radiologi',
            'audio_name' => 'r',
            'order' => 6
        ],
        
        // VIP/EKSEKUTIF SERVICES
        'LoketVIP' => [
            'type' => 'LoketVIP',
            'prefix' => 'E',
            'label' => 'LOKET VIP',
            'full_label' => 'LOKET PENDAFTARAN VIP/EKSEKUTIF',
            'category' => 'vip',
            'color' => [
                'from' => '#155724',
                'to' => '#0d3d1a',
                'text' => '#ffffff'
            ],
            'icon' => 'fa-door-open',
            'show' => 'panggil_loket_vip',
            'audio_name' => 'e',
            'order' => 7,
            'badge' => 'VIP'
        ],
        
        'CSVIP' => [
            'type' => 'CSVIP',
            'prefix' => 'V',
            'label' => 'CS VIP',
            'full_label' => 'CUSTOMER SERVICE VIP/EKSEKUTIF',
            'category' => 'vip',
            'color' => [
                'from' => '#004085',
                'to' => '#002752',
                'text' => '#ffffff'
            ],
            'icon' => 'fa-headset',
            'show' => 'panggil_cs_vip',
            'audio_name' => 'v',
            'order' => 8,
            'badge' => 'VIP'
        ],
        
        'ApotekVIP' => [
            'type' => 'ApotekVIP',
            'prefix' => 'G',
            'label' => 'APOTEK VIP',
            'full_label' => 'APOTEK VIP/EKSEKUTIF',
            'category' => 'vip',
            'color' => [
                'from' => '#ff8c00',
                'to' => '#cc7000',
                'text' => '#ffffff'
            ],
            'icon' => 'fa-pills',
            'show' => 'panggil_apotek_vip',
            'audio_name' => 'g',
            'order' => 9,
            'badge' => 'VIP'
        ],
        
        'IGDVIP' => [
            'type' => 'IGDVIP',
            'prefix' => 'H',
            'label' => 'IGD VIP',
            'full_label' => 'IGD VIP/EKSEKUTIF',
            'category' => 'vip',
            'color' => [
                'from' => '#a71d2a',
                'to' => '#7d161f',
                'text' => '#ffffff'
            ],
            'icon' => 'fa-ambulance',
            'show' => 'panggil_igd_vip',
            'audio_name' => 'h',
            'order' => 10,
            'badge' => 'VIP'
        ],
        
        'LaboratoriumVIP' => [
            'type' => 'LaboratoriumVIP',
            'prefix' => 'M',
            'label' => 'LAB VIP',
            'full_label' => 'LABORATORIUM VIP/EKSEKUTIF',
            'category' => 'vip',
            'color' => [
                'from' => '#b02a6c',
                'to' => '#851f50',
                'text' => '#ffffff'
            ],
            'icon' => 'fa-flask',
            'show' => 'panggil_lab_vip',
            'audio_name' => 'm',
            'order' => 11,
            'badge' => 'VIP'
        ],
        
        'RadiologiVIP' => [
            'type' => 'RadiologiVIP',
            'prefix' => 'S',
            'label' => 'RADIOLOGI VIP',
            'full_label' => 'RADIOLOGI VIP/EKSEKUTIF',
            'category' => 'vip',
            'color' => [
                'from' => '#0c7d92',
                'to' => '#095c6d',
                'text' => '#ffffff'
            ],
            'icon' => 'fa-x-ray',
            'show' => 'panggil_radiologi_vip',
            'audio_name' => 's',
            'order' => 12,
            'badge' => 'VIP'
        ],
    ],
    
    // ==========================================
    // HELPER METHODS
    // ==========================================
    
    /**
     * Get config by type
     */
    'get_by_type' => function($type) {
        $lokets = config('antrian.loket');
        return $lokets[$type] ?? null;
    },
    
    /**
     * Get config by show parameter
     */
    'get_by_show' => function($show) {
        $lokets = config('antrian.loket');
        foreach ($lokets as $config) {
            if ($config['show'] === $show) {
                return $config;
            }
        }
        return null;
    },
    
    /**
     * Get all reguler lokets
     */
    'get_reguler' => function() {
        $lokets = config('antrian.loket');
        return array_filter($lokets, function($config) {
            return $config['category'] === 'reguler';
        });
    },
    
    /**
     * Get all VIP lokets
     */
    'get_vip' => function() {
        $lokets = config('antrian.loket');
        return array_filter($lokets, function($config) {
            return $config['category'] === 'vip';
        });
    },
    
    // ==========================================
    // AUDIO CONFIGURATION
    // ==========================================
    
    'audio' => [
        'path' => 'plugins/anjungan/suara/',
        'extension' => '.wav',
        'prefix_files' => [
            'a', 'b', 'c', 'e', 'f', 'g', 'h', 'l', 'm', 'r', 's', 'v'
        ],
        'number_files' => [
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'nol', 'satu', 'dua', 'tiga', 'empat', 'lima', 
            'enam', 'tujuh', 'delapan', 'sembilan',
            'sepuluh', 'sebelas', 'belas', 'puluh', 
            'seratus', 'ratus'
        ],
        'system_files' => [
            'antrian', 'counter'
        ]
    ],
    
    // ==========================================
    // DISPLAY CONFIGURATION
    // ==========================================
    
    'display' => [
        'refresh_interval' => 3000, // milliseconds
        'show_completed' => true,
        'max_history' => 10,
        'separate_vip_display' => false, // set true jika pakai 2 TV terpisah
    ],
    
    // ==========================================
    // QUEUE CONFIGURATION
    // ==========================================
    
    'queue' => [
        'auto_reset' => true, // auto reset setiap hari
        'reset_time' => '00:00', // jam reset otomatis
        'max_queue_per_day' => 999, // maksimal nomor antrian per hari
    ]
];