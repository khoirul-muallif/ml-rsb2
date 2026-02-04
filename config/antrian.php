<?php
// config/antrian.php

return [
    'audio' => [
        'path' => 'plugins/anjungan/suara/',
        'extension' => '.wav'
    ],
    
    'loket' => [
        'Loket' => [
            'type' => 'Loket',
            'prefix' => 'A',
            'label' => 'LOKET',
            'full_label' => 'LOKET PENDAFTARAN',
            'color' => ['from' => '#0a7636', 'to' => '#15723b', 'text' => '#fff'],
            'icon' => 'fa-door-open',
            'show' => 'panggil_loket',
            'audio_name' => 'a',
            'category' => 'reguler',
            'order' => 1
        ],
        
        'LoketVIP' => [
            'type' => 'LoketVIP',
            'prefix' => 'AV',
            'label' => 'LOKET VIP',
            'full_label' => 'LOKET PENDAFTARAN VIP',
            'badge' => 'EXECUTIVE',
            'color' => ['from' => '#1e3a8a', 'to' => '#1e40af', 'text' => '#ffd700'], // Deep blue dengan gold text
            'icon' => 'fa-crown',
            'show' => 'panggil_loket_vip',
            'audio_name' => 'a',
            'category' => 'vip',
            'order' => 2
        ],
        
        'CS' => [
            'type' => 'CS',
            'prefix' => 'B',
            'label' => 'CS',
            'full_label' => 'CUSTOMER SERVICE',
            'color' => ['from' => '#0a8039', 'to' => '#198754', 'text' => '#fff'],
            'icon' => 'fa-headset',
            'show' => 'panggil_cs',
            'audio_name' => 'b',
            'category' => 'reguler',
            'order' => 3
        ],
        
        'CSVIP' => [
            'type' => 'CSVIP',
            'prefix' => 'BV',
            'label' => 'CS VIP',
            'full_label' => 'CUSTOMER SERVICE VIP',
            'badge' => 'EXECUTIVE',
            'color' => ['from' => '#134e4a', 'to' => '#0f766e', 'text' => '#fbbf24'], // Dark teal dengan gold text
            'icon' => 'fa-concierge-bell',
            'show' => 'panggil_cs_vip',
            'audio_name' => 'b',
            'category' => 'vip',
            'order' => 4
        ],
        
        'Apotek' => [
            'type' => 'Apotek',
            'prefix' => 'F',
            'label' => 'APOTEK',
            'full_label' => 'APOTEK',
            'color' => ['from' => '#10b981', 'to' => '#14b8a6', 'text' => '#fff'],
            'icon' => 'fa-pills',
            'show' => 'panggil_apotek',
            'audio_name' => 'f',
            'category' => 'reguler',
            'order' => 5
        ],
    ]
];