<?php

namespace Engine\Controllers;

use Engine\Config\Config;
use Engine\Filesystem\SimpleImage;
use Engine\Http\Http;
use Engine\View\View;

class Controller {

    protected $urlvalues;
    protected $action;

    /**
     * path from root of project to views folder 
     * can be set to use some specific path
     * @var string 
     */
    protected $viewPath = '';

    /**
     * layout file
     * @var string 
     */
    protected $layout = 'engine.views.layouts.default';

    /**
     * page title
     * @var string
     */
    protected $title = 'default title';

    
    protected $view;
    /**
     * 
     * @param type $action - action to execute
     * @param type $urlvalues - values parsed from url
     */
    public function __construct($action=null, $urlvalues=[]) {
        $this->action = $action;
        $this->urlvalues = $urlvalues;
        
        $this->view = new View();
    }

    /**
     * This method is invoked right before an action is to be executed
     * override this method to do last-minute preparation for the action.
     * @param string $action the action to be executed.
     * @return boolean whether the action should be executed.
     */
    protected function beforeAction($action) {
        return true;
    }

    /**
     * Executes action requested by url
     */
    public function executeAction() {
        if ($this->beforeAction($this->action)) {
            $result = call_user_func_array([$this, $this->action], $this->urlvalues);

            if ($result) {
                echo $result;
            }
        }
    }

    /**
     * Renders a view with a layout.
     *
     * This method first calls renderPartial to render the view.
     * In the layout view, the content view rendering result can be accessed via variable $content. 
     *
     * By default, the layout view script is "Engine/Views/Layouts/default.php".
     * This may be customized by changing layout property of controller. 
     * 
     * @param string $viewName view file
     * @param array $data data to be extracted and made available to the view file
     * @param boolean $return whether the rendering result should be returned as a string
     * @return string the rendering result. Null if the rendering result is not required.
     */
    public function render($viewName, $data = null, $return = false) {
        
        $output = $this->renderPartial($viewName, $data, true);
        
        if (($layoutFile = $this->view->getLayoutFile($this->layout)) !== false) {
            $output = $this->view->renderViewFile($layoutFile, ['content' => $output], true);
        }

        if ($return) {
            return $output;
        } else {
            echo $output;
        }
    }

    /**
     * 
     * Renders a view.
     *
     * If $data is an associative array,
     * it will be extracted as PHP variables and made available to the script.
     *
     * This method differs from render() in that it does not
     * apply a layout to the rendered result. 
     * 
     * @param string $viewName view file
     * @param array $data data to be extracted and made available to the view file
     * @param boolean $return whether the rendering result should be returned as a string
     * @return string the rendering result. Null if the rendering result is not required.
     */
    public function renderPartial($viewName, $data = null, $return = false) {
        $viewFile = $this->view->getViewFile($viewName, $this->viewPath);
        $output = $this->view->renderViewFile($viewFile, $data, $return);

        if ($return) {
            return $output;
        } else {
            echo $output;
        }
    }


    /**
     * Redirects the browser to the specified URL.
     * @param string $url URL to be redirected to. 
     * @param boolean $terminate whether to terminate the current application
     * @param integer $statusCode the HTTP status code. Defaults to 302. 
     */
    public function redirect($url, $terminate = true, $statusCode = 302) {
        (new Http)->redirect($url, $terminate, $statusCode);
    }

    /**
     * send JSON.
     *
     * @param $data
     * @param $code
     */
    protected function sendJSON($data = [], $code = 200) {
        (new Http)->sendJSON($data, $code);
    }
    
    protected function saveFile($file, $folder = null){
        
        if($folder==null){
            $folder = 'public'.DIRECTORY_SEPARATOR.'files';
        }
        
        $absolutePath = Config::getBasePath().DIRECTORY_SEPARATOR.$folder;
        
        if(!is_dir($folder)){
            mkdir($folder, 775, true);
        }
        
        $info = pathinfo($file['name']);
        $ext = $info['extension'];
        $newname = uniqid().".".$ext; 

        
        $appPath = DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$newname;
        
        $destination = $absolutePath.DIRECTORY_SEPARATOR.$newname;
        
        return move_uploaded_file($file['tmp_name'], $destination)?$appPath:false;
    }
    
    
    function saveImage($html_element_name, $new_img_width, $new_img_height, $folder = null) {
        if(empty($_FILES[$html_element_name]['name'])){
            return false;
        }
        
        $info = pathinfo($_FILES[$html_element_name]['name']);
        $ext = $info['extension'];
        $newname = uniqid().".".$ext; 
        
        if($folder==null){
            $folder = 'public'.DIRECTORY_SEPARATOR.'files';
        }
        
        $target_dir = Config::getBasePath().DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR;
        if(!is_dir($target_dir)){
            mkdir($target_dir, 775, true);
        }
        
        $appPath = DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$newname;
        
        $target_file = $target_dir . $newname;

        $image = new SimpleImage();
        $image->load($_FILES[$html_element_name]['tmp_name']);
        $image->resize($new_img_width, $new_img_height);
        $image->save($target_file);
        
        return $appPath; 

    }

}
