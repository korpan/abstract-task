<?php

namespace Engine\Database;

use Engine\Config\Config;


class Connection {

    public static function getConnection(){
        $config = Config::getInstance()->getConfig(Config::DATABASE);
        
        switch ($config['default']):
            case 'mysql':
                $config = $config['connections']['mysql'];
                $connection = MySqlConnection::instantiate($config['host'], $config['database'], $config['username'], $config['password']);
                break;
        endswitch;
        
        return $connection;
    }
}
