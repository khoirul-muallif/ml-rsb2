<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MliteSetting extends Model
{
    protected $table = 'mlite_settings';
    
    protected $fillable = [
        'module',
        'field',
        'value'
    ];

    /**
     * Get setting value
     * 
     * @param string $module Module name (e.g., 'anjungan', 'settings')
     * @param string $field Field name (e.g., 'antrian_loket', 'panggil_loket_nomor')
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public static function getSetting($module, $field, $default = null)
    {
        $setting = self::where('module', $module)
            ->where('field', $field)
            ->first();
        
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value (create or update)
     * 
     * @param string $module Module name
     * @param string $field Field name
     * @param mixed $value Value to set
     * @return bool
     */
    public static function setSetting($module, $field, $value)
    {
        return self::updateOrCreate(
            [
                'module' => $module,
                'field' => $field
            ],
            [
                'value' => $value
            ]
        );
    }

    /**
     * Get all settings for a module
     * 
     * @param string $module Module name
     * @return \Illuminate\Support\Collection
     */
    public static function getModuleSettings($module)
    {
        return self::where('module', $module)
            ->pluck('value', 'field');
    }

    /**
     * Set multiple settings at once
     * 
     * @param string $module Module name
     * @param array $settings Associative array of field => value
     * @return bool
     */
    public static function setMultipleSettings($module, array $settings)
    {
        foreach ($settings as $field => $value) {
            self::setSetting($module, $field, $value);
        }
        
        return true;
    }
}