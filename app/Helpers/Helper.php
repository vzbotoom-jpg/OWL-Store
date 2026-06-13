<?php

namespace App\Helpers;

use App\Models\Setting;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        try {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}