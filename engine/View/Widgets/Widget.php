<?php

namespace Engine\View\Widgets;

use Engine\View\View;


abstract class Widget{
    /**
     *
     * @var View 
     */
    public $view;
    
    public $viewFile;
    
    
    public function __construct($config) {
        foreach ($config as $attr=>$value) {
            $this->$attr = $value;
        }
        
        $this->view = new View();
    }
    
    abstract public function run();
    
    public function render($viewName, $data){
        $viewFile = $this->view->getViewFile($viewName);
        return $this->view->renderViewFile($viewFile, $data, true);
    }
    
}
