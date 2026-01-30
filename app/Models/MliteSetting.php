<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MliteSetting extends Model
{
    public $timestamps = false;
    protected $table = 'ml_settings';
    protected $primaryKey = 'id';
    
    protected $fillable = ['module', 'field', 'value'];
    
    /**
     * Get setting value with caching
     * 
     * @param string $module
     * @param string $field
     * @param mixed $default
     * @return mixed
     */
    public static function getSetting($module, $field, $default = null)
    {
        $cacheKey = "mlite_setting_{$module}_{$field}";
        
        return Cache::remember($cacheKey, 3600, function() use ($module, $field, $default) {
            $setting = self::where('module', $module)
                ->where('field', $field)
                ->first();
            
            return $setting ? $setting->value : $default;
        });
    }
    
    /**
     * Set setting value and clear cache
     * 
     * @param string $module
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    public static function setSetting($module, $field, $value)
    {
        $cacheKey = "mlite_setting_{$module}_{$field}";
        Cache::forget($cacheKey);
        
        return self::updateOrCreate(
            ['module' => $module, 'field' => $field],
            ['value' => $value]
        );
    }
    
    /**
     * Get all settings for a module
     * 
     * @param string $module
     * @return array
     */
    public static function getModuleSettings($module)
    {
        $cacheKey = "ml_settings_module_{$module}";
        
        return Cache::remember($cacheKey, 3600, function() use ($module) {
            return self::where('module', $module)
                ->pluck('value', 'field')
                ->toArray();
        });
    }
    
    /**
     * Clear all settings cache
     * 
     * @return void
     */
    public static function clearCache()
    {
        Cache::flush();
    }
}