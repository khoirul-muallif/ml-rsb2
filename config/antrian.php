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
            'color' => ['from' => '#28a745', 'to' => '#20c997', 'text' => '#fff'],
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
            'color' => ['from' => '#ffd700', 'to' => '#ff9800', 'text' => '#000'],
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
            'color' => ['from' => '#007bff', 'to' => '#0056b3', 'text' => '#fff'],
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
            'color' => ['from' => '#6f42c1', 'to' => '#5a32a3', 'text' => '#fff'],
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
            'color' => ['from' => '#ffc107', 'to' => '#ff9800', 'text' => '#000'],
            'icon' => 'fa-pills',
            'show' => 'panggil_apotek',
            'audio_name' => 'f',
            'category' => 'reguler',
            'order' => 5
        ],
    ]
];