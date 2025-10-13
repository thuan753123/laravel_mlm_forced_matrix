<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Get setting value with proper type casting.
     */
    public function getValueAttribute($value)
    {
        switch ($this->type) {
            case 'integer':
                return (int) $value;
            case 'boolean':
                return (bool) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Set setting value with proper type conversion.
     */
    public function setValueAttribute($value)
    {
        switch ($this->type) {
            case 'json':
                $this->attributes['value'] = json_encode($value);
                break;
            case 'boolean':
                $this->attributes['value'] = $value ? '1' : '0';
                break;
            default:
                $this->attributes['value'] = (string) $value;
        }
    }

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return $setting->value;
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, $value, string $type = 'string'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }

    /**
     * Get multiple settings by keys.
     */
    public static function getMany(array $keys): array
    {
        $settings = static::whereIn('key', $keys)->get();
        
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->key] = $setting->value;
        }
        
        return $result;
    }

    /**
     * Set multiple settings.
     */
    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $type = 'string';
            if (is_array($value)) {
                $type = 'json';
            } elseif (is_bool($value)) {
                $type = 'boolean';
            } elseif (is_int($value)) {
                $type = 'integer';
            }
            
            static::set($key, $value, $type);
        }
    }

    /**
     * Get all MLM settings.
     */
    public static function getMlmSettings(): array
    {
        $keys = [
            'mlm_width',
            'mlm_max_depth',
            'mlm_commissions',
            'mlm_spillover_mode',
            'mlm_placement_mode',
            'mlm_capping_per_cycle',
            'mlm_cycle_period',
            'mlm_qualify_rules',
        ];
        
        return static::getMany($keys);
    }

    /**
     * Update MLM settings.
     */
    public static function updateMlmSettings(array $settings): void
    {
        static::setMany($settings);
    }
}