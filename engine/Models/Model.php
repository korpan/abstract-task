<?php

namespace Engine\Models;

use Engine\Database\Connection;
use Engine\Database\Interfaces\DatabaseConnectionInterface;
use Engine\Validators\Validator;

class Model {

    protected $table = '';
    protected $attributes = [];

    /**
     *
     * primary key field
     * 
     * @var string 
     */
    protected $pk = 'id';

    /**
     *
     * @var DatabaseConnectionInterface 
     */
    protected $dbConnection;

    /**
     * Indicates if the model exists.
     *
     * @var bool
     */
    protected $exists = false;

    /**
     * validation errors
     * @var array
     */
    protected $_errors = [];
    private $_dbAttributes = [];

    public function __construct() {
        $this->dbConnection = Connection::getConnection();
    }

    public function __get($name) {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        } else if(method_exists($this, 'get'.ucfirst($name))){
            $method = 'get'.ucfirst($name);
            return $this->$method();
        }else{
            return null;
        }
    }

    public function __set($name, $val) {
        $this->attributes[$name] = $val;
    }

    public function __isset($name) {
        return isset($this->attributes[$name]) || method_exists($this, 'get'.ucfirst($name));
    }

    /**
     * Returns the model of the specified model class.
     * 
     * @return model instance.
     */
    public static function model() {
        $model = new static();
        return $model;
    }

    protected function instantiate($exists = false) {
        $model = new static();
        $model->exists = $exists;
        return $model;
    }

    public function getDbAttributes() {
        if (empty($this->_dbAttributes)) {
            if (empty($this->table)) {
                return false;
            }
            $this->_dbAttributes = $this->dbConnection->getColumnNames($this->table);
        }

        return $this->_dbAttributes;
    }

    public function save($validate = true) {

        if ($validate && !$this->validate()) {
            return false;
        }

        if ($this->exists) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    public function update() {
        if (!$this->exists) {
            return $this->insert();
        }

        $attributes = $this->prepareDbAttributes();

        return $this->updateByPk($this->{$this->pk}, $attributes);
    }

    public function updateByPk($pk, $attributes) {
        $where = [
            $this->pk => $pk,
        ];
        return $this->dbConnection->update($this->table, $attributes, $where);
    }

    /**
     * form an array of attributes for insert or update
     * 
     * @return array of attributes
     */
    public function prepareDbAttributes() {
        $attributes = [];
        foreach ($this->getDbAttributes() as $attr) {
            //for the sake of simplicity we just assume that pk is autoincremented integer, 
            //so it should not be inserted or updated
            if ($attr == $this->pk) {
                continue;
            } else {
                if (isset($this->attributes[$attr])) {
                    $attributes[$attr] = $this->attributes[$attr];
                }
            }
        }
        return $attributes;
    }

    public function insert() {
        $attributes = $this->prepareDbAttributes();

        if (($id = $this->dbConnection->insert($this->table, $attributes)) !== false) {
            $this->exists = true;
            $this->id = $id;
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * find one record by attributes
     * 
     * @param array $attributes - array of attributes with corresponding values to be serched by
     */
    public function findByAttributes($attributes) {
        $data = $this->dbConnection->getWhere($this->table, $attributes);
        if ($data != false) {
            return $this->instantiate(true)->hydrate(reset($data));
        } else {
            return null;
        }
    }

    public function hydrate($data) {
        foreach ($data as $attr=>$value) {
            $this->attributes[$attr] = $value;
        }
        return $this;
    }

    public function findAll($attributes = []) {
        $data = $this->dbConnection->getWhere($this->table, $attributes, null);

        $models = [];
        foreach ($data as $_model) {
            $models[] = $this->instantiate(true)->hydrate($_model);
        }
        return $models;
    }

    public function validate() {
        return (new Validator())->validate($this);
    }

    public function findByPk($pkValue) {
        return $this->findByAttributes([$this->pk => $pkValue]);
    }

    public function addError($attr) {
        $this->_errors[] = $attr;
    }

    public function getErrors() {
        return $this->_errors;
    }

    /**
     * 
     * Returns the validation rules for attributes.
     *
     * This method should be overridden to declare validation rules.
     * 
     * @return array validation rules to be applied when validate() is called.
     */
    public function rules() {
        return [];
    }

}
