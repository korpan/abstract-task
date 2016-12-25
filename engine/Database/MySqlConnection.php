<?php

namespace Engine\Database;

use Engine\Database\Interfaces\DatabaseConnectionInterface;
use Exception;
use PDO;
use PDOException;

class MySqlConnection implements DatabaseConnectionInterface {

    protected static $instance;
    protected $PDO;

    
    //TODO refactor
    //but for now i only need this much
    protected $mySqlExpressions = [
        'IS NOT NULL',
        'IS NULL'
    ];

    private function __construct($host, $dbname, $user, $pass) {
        try {
            $this->PDO = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    private function __clone() {}

    private function __wakeup() {}

    public static function instantiate($host, $dbname, $user, $pass) {
        if (!isset(self::$instance)) {
            self::$instance = new self($host, $dbname, $user, $pass);
        }
        return self::$instance;
    }

    public function getColumnNames($table) {
        $sql = 'SHOW COLUMNS FROM ' . $table;

        $statement = $this->PDO->prepare($sql);

        try {
            $columns = [];
            if ($statement->execute()) {
                $raw_column_data = $statement->fetchAll();
                foreach ($raw_column_data as $column) {
                    foreach ($column as $key => $value) {
                        if ($key === 'Field') {
                            $columns[] = $value;
                        }
                    }
                }
            }
            return $columns;
        } catch (Exception $e) {
            return [];
        }
    }

    
    public function isMySqlExpression($value){
        $expression = strtoupper(trim($value));
        return in_array($expression, $this->mySqlExpressions);
    }
    
    public function getWhere($table, $_where, $limit = 1) {
        $dbColumns = $this->getColumnNames($table);

        $where = [];
        $params = [];
        foreach ($_where as $attribute => $value) {
            if (in_array($attribute, $dbColumns)) {
                if($this->isMySqlExpression($value)){
                    $expression = strtoupper(trim($value));
                    $where[] = "`{$attribute}` {$expression}";
                }else{
                    $params[":{$attribute}"] = $value;
                    $where[] = "`{$attribute}` = :{$attribute}"; 
                }
            }
        }

        $whereStr = !empty($where) ? "WHERE " . implode(' AND ', $where) : '';

        $limitStr = $limit > 0 ? "LIMIT {$limit}" : '';

        $query = "SELECT * FROM `{$table}` {$whereStr} {$limitStr}";

        $statement = $this->prepareStatement($query, $params);
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
    
    
    public function prepareStatement($query, $bindings = []){
        $statement = $this->PDO->prepare($query);
        foreach ($bindings as $key => $value) {
            $statement->bindValue($key, $value);
        }
        return $statement;
    }

    public function insert($table, $attributes) {
        if (empty($attributes)) {
            return false;
        }
        
        $columns = [];
        $values = [];

        foreach ($attributes as $key => $value) {
            $columns[] = $key;
            $values[":{$key}"] = $value;
        }
        
        $columns = implode(',', $columns);
        $aliases = implode(',', array_flip($values));

        $query = "INSERT INTO `{$table}` ({$columns}) VALUES ({$aliases})";

        $statement = $this->prepareStatement($query, $values);
        if ($statement->execute()) {
            return $this->PDO->lastInsertId();
        } else {
            return false;
        }
    }
    
    public function update($table, $attributes, $_where) {
        if (empty($attributes)) {
            return false;
        }

        $set = [];
        $params = [];

        foreach ($attributes as $key => $value) {
            $set[] = "$key=:$key";
            $params[":$key"] = $value;
        }
        
        $where = [];
        foreach ($_where as $key => $value) {
            $where[] = "$key=:$key" . '_where';
            $params[":$key" . '_where'] = $value;
        }
        
        $setStr = implode(',', $set);
        $whereStr = !empty($where) ? "WHERE " .implode(',', $where) : '';

        $query = "UPDATE `{$table}` SET {$setStr} WHERE {$whereStr}";

        $statement = $this->prepareStatement($query, $params);
        if ($statement->execute()) {
            return $this->PDO->lastInsertId();
        } else {
            return false;
        }
    }
    
    public function selectRaw($query, $params = []){
        $statement = $this->prepareStatement($query, $params);
        
        if($statement->execute()){
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return [];
        }
    }

}
