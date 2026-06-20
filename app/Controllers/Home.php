<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\ApplicationModel;

class Home extends BaseController
{
    public function index()
    {
        $appModel = new ApplicationModel();
        
        $data = [
            'title' => 'Dashboard | WA Gateway',
            'total_apps' => $appModel->countAllResults()
        ];
        
        return view('dashboard/index', $data);
    }
}
