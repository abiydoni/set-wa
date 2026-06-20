<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'tb_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['setting_key', 'setting_value', 'description'];

    public function getSetting($key)
    {
        $setting = $this->where('setting_key', $key)->first();
        return $setting ? $setting['setting_value'] : null;
    }

    public function updateSetting($key, $value)
    {
        $setting = $this->where('setting_key', $key)->first();
        if ($setting) {
            return $this->update($setting['id'], ['setting_value' => $value]);
        }
        return false;
    }
}
