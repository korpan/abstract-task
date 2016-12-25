<?php

namespace Engine\Routing;

class Router {
    private static $uri = null;

    
    private static $routes = [];

    public static function setRoutes($routes) {
        self::$routes = $routes;
    }

    public static function getUri() {
        if(self::$uri==null){
            $uri = $_SERVER['REQUEST_URI'];
            self::$uri = parse_url(trim($uri, '/'), PHP_URL_PATH);
        }
        
        return self::$uri;
    }

    public static function getUriElements() {
        return explode('/', self::getUri());
    }

    public static function matchUri($uri) {

        $routes = self::$routes;

        $params = array();

        foreach ($routes as $route) {
            //we keep our route uri in the [0] position
            $route_uri = array_shift($route);

            $regex_uri = self::makeRegexUri($route_uri);

            if (!preg_match($regex_uri, $uri, $match)) {
                continue;
            } else {
                foreach ($match as $key => $value) {
                    if (is_int($key)) {
                        //removing preg_match digit keys
                        continue;
                    }

                    $params[$key] = $value;
                }

                //if no values are set, load default ones
                foreach ($route as $key => $value) {
                    if (!isset($params[$key])) {
                        $params[$key] = $value;
                    }
                }

                break;
            }
        }

        return $params;
    }

    private static function makeRegexUri($uri) {
        $uri = trim($uri, '/');

        $reg_escape = '[.\\+*?[^\\]${}=!|]';
        $expression = preg_replace('#' . $reg_escape . '#', '\\\\$0', $uri);

        if (strpos($expression, '(') !== FALSE) {
            $expression = str_replace(array('(', ')'), array('(?:', ')?'), $expression);
        }

        $reg_segment = '[^/.,;?\n]++';
        $expression = str_replace(array('<', '>'), array('(?P<', '>' . $reg_segment . ')'), $expression);

        return '#^' . $expression . '$#uD';
    }

}
