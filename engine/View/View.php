<?php

namespace Engine\View;

use Engine\Config\Config;
use Engine\Filesystem\File;


class View {
    
    protected $baseFilePath;

    public function __construct() {
        
        $this->baseFilePath = Config::getBasePath();
    }
    
    
    /**
     * returns layout file
     * 
     * @param type $layout
     * @return boolean|string
     */
    public function getLayoutFile($layout) {
        if (empty($layout)) {
            return false;
        }

        $layoutFile = File::getPath($this->baseFilePath . DIRECTORY_SEPARATOR . $layout) . '.php';

        if (file_exists($layoutFile)) {
            return $layoutFile;
        } else {
            return false;
        }
    }
    
    /**
     * Renders a view file.
     * This method includes the view file as a PHP script
     * and captures the display result if required.
     * @param string $viewFile view file
     * @param array $data data to be extracted and made available to the view file
     * @param boolean $return whether the rendering result should be returned as a string
     * @return string the rendering result. Null if the rendering result is not required.
     */
    public function renderViewFile($viewFile, $data = null, $return = false) {
        // use special variable names here to avoid conflict when extracting data
        if (is_array($data)) {
            extract($data, EXTR_PREFIX_SAME, 'data');
        } else {
            $data = $data;
        }
        
        if ($return) {
            ob_start();
            ob_implicit_flush(false);
            require $viewFile;
            return ob_get_clean();
        } else {
            require $viewFile;
        }
    }
    /**
     * Return path of view file
     * @param type $viewName
     * @param type $viewPath
     * @return string|boolean
     */
    public function getViewFile($viewName, $viewPath=null) {
        if (empty($viewName)) {
            return false;
        }
        
        if(empty($viewPath)){
            $viewFile = File::getPath($this->baseFilePath . DIRECTORY_SEPARATOR .$viewName) . '.php';
        }else{
            $viewFile = File::getPath($this->baseFilePath . DIRECTORY_SEPARATOR . $viewPath . DIRECTORY_SEPARATOR . $viewName) . '.php';
        }
        
        if (file_exists($viewFile)) {
            return $viewFile;
        } else {
            return false;
        }
    }
    
    /**
     * 
     * @param string $className - name of the widget
     * @param array $config - parameters passed to widget
     * @param boolean $captureOutput - whether the rendering result should be returned as a string
     */
    public function widget($className, $config = [], $captureOutput = false) {
        if (class_exists($className)) {
            $widget = new $className($config);
            
            ob_start();
            ob_implicit_flush(false);

            echo $widget->run();

            $content = ob_get_clean();
            
            if ($captureOutput) {
                return $content;
            } else {
                echo $content;
            }
        }
    }
    
}
