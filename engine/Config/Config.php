<?php

namespace Engine\Config;

class Config {
    
    const APP = 'app';
    const DATABASE = 'database';
    
    protected static $instance;

    protected $config = [];

    private function __construct($config) {
        $this->config = $config;
    }
    private function __clone() {}
    private function __wakeup() {}

    public static function instantiate($config) {
        if (!isset(self::$instance)) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }
    
    
    /**
     * 
     * @return Config
     */
    public static function getInstance(){
        return self::$instance;
    }
    
    public function getConfig($for){
        return $this->config[$for];
    }
    
    public static function getBasePath(){
        return self::getInstance()->getConfig(Config::APP)['paths']['base'];
    }
}
