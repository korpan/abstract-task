<?php

namespace App\Controllers;

class SiteController extends BaseController{
    
    protected $layout = 'app.views.layouts.main';
    
    
    public function index(){
        $this->render('site.index');
    }
    
}
