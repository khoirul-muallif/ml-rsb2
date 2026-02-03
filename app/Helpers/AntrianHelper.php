<?php

namespace App\Helpers;

class AntrianHelper
{
    /**
     * Get loket config by type
     * 
     * @param string $type
     * @return array|null
     */
    public static function getByType($type)
    {
        $lokets = config('antrian.loket');
        return $lokets[$type] ?? null;
    }
    
    /**
     * Get loket config by show parameter
     * 
     * @param string $show
     * @return array|null
     */
    public static function getByShow($show)
    {
        $lokets = config('antrian.loket');
        
        foreach ($lokets as $config) {
            if ($config['show'] === $show) {
                return $config;
            }
        }
        
        return null;
    }
    
    /**
     * Get loket config by prefix
     * 
     * @param string $prefix
     * @return array|null
     */
    public static function getByPrefix($prefix)
    {
        $lokets = config('antrian.loket');
        
        foreach ($lokets as $config) {
            if ($config['prefix'] === $prefix) {
                return $config;
            }
        }
        
        return null;
    }
    
    /**
     * Get all reguler lokets
     * 
     * @return array
     */
    public static function getReguler()
    {
        $lokets = config('antrian.loket');
        
        return array_filter($lokets, function($config) {
            return $config['category'] === 'reguler';
        });
    }
    
    /**
     * Get all VIP lokets
     * 
     * @return array
     */
    public static function getVIP()
    {
        $lokets = config('antrian.loket');
        
        return array_filter($lokets, function($config) {
            return $config['category'] === 'vip';
        });
    }
    
    /**
     * Get all lokets sorted by order
     * 
     * @return array
     */
    public static function getAllSorted()
    {
        $lokets = config('antrian.loket');
        
        uasort($lokets, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });
        
        return $lokets;
    }
    
    /**
     * Get category by type
     * 
     * @param string $type
     * @return string
     */
    public static function getCategoryByType($type)
    {
        $config = self::getByType($type);
        return $config['category'] ?? 'reguler';
    }
    
    /**
     * Check if type is VIP
     * 
     * @param string $type
     * @return bool
     */
    public static function isVIP($type)
    {
        return self::getCategoryByType($type) === 'vip';
    }
    
    /**
     * Get audio path for a file
     * 
     * @param string $filename
     * @return string
     */
    public static function getAudioPath($filename)
    {
        $config = config('antrian.audio');
        return asset($config['path'] . $filename . $config['extension']);
    }
    
    /**
     * Convert number to Indonesian audio file names
     * Supports: 0-9999
     */ 
    public static function convertNumberToAudio($number)
    {
        $files = [];
        
        if (!is_numeric($number) || $number < 0) {
            return ['nol'];
        }
        
        if ($number == 0) {
            return ['nol'];
        }
        
        // ✅ Handle thousands (1000-9999)
        if ($number >= 1000) {
            $thousands = floor($number / 1000);
            if ($thousands == 1) {
                $files[] = 'seribu';
            } else {
                $files[] = self::getDigitName($thousands);
                $files[] = 'ribu';
            }
            $number = $number % 1000;
        }
        
        // Handle hundreds (100-999)
        if ($number >= 100) {
            $hundreds = floor($number / 100);
            if ($hundreds == 1) {
                $files[] = 'seratus';
            } else {
                $files[] = self::getDigitName($hundreds);
                $files[] = 'ratus';
            }
            $number = $number % 100;
        }
        
        // Handle tens (10-99)
        if ($number >= 20) {
            $tens = floor($number / 10);
            $files[] = self::getDigitName($tens);
            $files[] = 'puluh';
            $number = $number % 10;
            
            if ($number > 0) {
                $files[] = self::getDigitName($number);
            }
        } elseif ($number >= 11) {
            $files[] = self::getDigitName($number % 10);
            $files[] = 'belas';
        } elseif ($number == 10) {
            $files[] = 'sepuluh';
        } elseif ($number > 0) {
            $files[] = self::getDigitName($number);
        }
        
        return $files;
    }
    
    /**
     * Get digit name in Indonesian
     * 
     * @param int $digit
     * @return string
     */
    private static function getDigitName($digit)
    {
        $names = [
            0 => 'nol', 
            1 => 'satu', 
            2 => 'dua', 
            3 => 'tiga', 
            4 => 'empat',
            5 => 'lima', 
            6 => 'enam', 
            7 => 'tujuh', 
            8 => 'delapan', 
            9 => 'sembilan'
        ];
        
        return $names[$digit] ?? 'nol';
    }
    
    /**
     * Generate complete audio sequence for announcement
     * 
     * @param string $prefix Audio prefix (a, b, e, v, etc)
     * @param int $number Queue number
     * @param int $counter Counter number
     * @return array
     */
    public static function generateAudioSequence($prefix, $number, $counter)
    {
        $files = ['antrian', $prefix];
        
        // Add queue number
        $numberFiles = self::convertNumberToAudio($number);
        $files = array_merge($files, $numberFiles);
        
        // Add counter
        $files[] = 'counter';
        $counterFiles = self::convertNumberToAudio($counter);
        $files = array_merge($files, $counterFiles);
        
        return $files;
    }
    
    /**
     * Validate if audio files exist
     * 
     * @param array $audioFiles
     * @return array Missing files
     */
    public static function validateAudioFiles($audioFiles)
    {
        $missing = [];
        $config = config('antrian.audio');
        $basePath = public_path($config['path']);
        
        foreach ($audioFiles as $file) {
            $fullPath = $basePath . $file . $config['extension'];
            if (!file_exists($fullPath)) {
                $missing[] = $file;
            }
        }
        
        return $missing;
    }
}