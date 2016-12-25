<?php

namespace Engine;

use Engine\Auth\Auth;
use Engine\Controllers\Controller;
use Engine\Exceptions\NotFoundException;
use Engine\Routing\Router;
use Exception;

class App{
    
    public function run(){

        Auth::startSession();
        
        try{
            $this->_run();
        } catch (Exception $ex) {
            if(defined('DEBUG') && DEBUG){
                throw $ex;
            }else{
                if(is_a($ex, '\Engine\Exceptions\NotFoundException')){
                    header("HTTP/1.1 404 Not Found");
                }else{
                    header("HTTP/1.1 500 Internal Server Error");
                }
                die;
            }
        }
    }
    
    private function _run(){
        $uri = Router::getUri();
        
        if ($params = Router::matchUri($uri)){
            $controller = $params['controller'];
            $action = $params['action'];
            
            if(strpos($action, '-')!==false){
                $action = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $action))));
            }
            
            unset($params['controller'], $params['action']);
            if (class_exists($controller)){
                if (method_exists($controller, $action)){
                    (new $controller($action, $params))->executeAction();
                }else{
                    throw new NotFoundException('No action found '.$action);
                }
            }else{
                throw new NotFoundException('No controller found '.$controller);
            }
        }else{
            throw new NotFoundException('No route found '.$uri);
        }
    }
    
    
    public static function bindRoutes($routes){
        Router::setRoutes($routes);
    }
}