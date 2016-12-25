<?php

namespace Engine\View\Widgets;

use Engine\Routing\Router;

class BreadCrumbsWidget extends Widget{
    
    public $viewFile = 'engine.View.Widgets.views.breadcrumbs';
    
    public function run() {
        
        if(Router::getUri()==''){
            return;
        }
        
        $_uri_elements = Router::getUriElements();
        $uri_elements = array_splice($_uri_elements, 0, count($_uri_elements)-1);
        
        
        $crumbs = [];
        
        $crumbs[] = [
            'url'=> '',
            'text'=> 'Home'
        ];
        
        $path = [];
        foreach ($uri_elements as $uri_element) {
            $path[] = $uri_element;
            
            $crumbs[] = [
                'url'=> implode('/', $path),
                'text'=> ucfirst($uri_element)
            ];
        }
        
        return $this->render($this->viewFile, [
            'crumbs' => $crumbs,
        ]);
    }
    
}
