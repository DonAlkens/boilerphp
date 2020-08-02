<?php

namespace App\Core\Database;


class Diagram {

    public $columns = array();

    public function __construct($table_name, $query, $primary_keys, $foreignKey)
    {
        return $this->createTableQuery($table_name, $query, $primary_keys, $foreignKey);
    }
    
    public function createTableQuery($table_name, $query, $primary_keys, $foreignKey) {

        $this->TableQuery = "CREATE TABLE IF NOT EXISTS `$table_name` ($query";
        if($primary_keys != "") {
            $this->TableQuery .= ", PRIMARY KEY ($primary_keys)";
        }
        if($foreignKey != "") {
            $this->TableQuery .= $foreignKey; 
        }
        $this->TableQuery .= " )";
        return $this->TableQuery;
    }
}