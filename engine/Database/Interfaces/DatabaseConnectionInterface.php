<?php

namespace Engine\Database\Interfaces;

interface DatabaseConnectionInterface {

    function getColumnNames($table);
    function getWhere($table, $where);
    function insert($table, $attributes);
    function update($table, $attributes, $where);
    
    function selectRaw($query, $params = []);
    
}
