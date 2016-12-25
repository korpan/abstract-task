<?php

namespace Engine\View\Widgets;

class SidebarNavigationWidget extends Widget{
    
    public $viewFile = 'engine.View.Widgets.views.sidebar_navigation';

    protected $nav_tree = [];


    public function run() {
        return $this->render($this->viewFile, [
            'nav_tree' => $this->nav_tree
        ]);
    }
    
    
}
