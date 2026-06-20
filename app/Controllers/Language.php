<?php

namespace App\Controllers;

class Language extends BaseController
{
    public function switch($locale)
    {
        $supported = config('App')->supportedLocales;
        
        if (in_array($locale, $supported)) {
            $this->session->set('locale', $locale);
        }
        
        return redirect()->back();
    }
}
