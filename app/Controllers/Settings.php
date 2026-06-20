<?php

namespace App\Controllers;

use App\Models\SettingModel;

class Settings extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        $settings = $this->settingModel->findAll();
        $data['title'] = 'Settings | WA Gateway';
        $data['settings'] = [];
        
        foreach ($settings as $setting) {
            $data['settings'][$setting['setting_key']] = $setting;
        }

        return view('settings/index', $data);
    }

    public function save()
    {
        $postData = $this->request->getPost();
        
        foreach ($postData as $key => $value) {
            $this->settingModel->updateSetting($key, $value);
        }

        return redirect()->to(base_url('settings'))->with('success', 'Pengaturan berhasil disimpan!');
    }

    public function getDefaultDb()
    {
        $settings = $this->settingModel->findAll();
        $def = [];
        foreach ($settings as $s) {
            $def[$s['setting_key']] = $s['setting_value'];
        }

        return $this->response->setJSON([
            'host' => $def['defaultDbHost'] ?? 'localhost',
            'name' => $def['defaultDbName'] ?? '',
            'user' => $def['defaultDbUser'] ?? '',
            'pass' => $def['defaultDbPass'] ?? ''
        ]);
    }
}
