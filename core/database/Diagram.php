<?php

namespace App\Core\Database;


class Diagram {

    public $columns = array();

    public function __construct($table_name, $query, $primary_keys)
    {
        $this->createTableQuery($table_name, $query, $primary_keys);
    }
    
    public function createTableQuery($table_name, $query, $primary_keys) {

        $this->TableQuery = "CREATE TABLE IF NOT EXISTS `$table_name` ($query";
        if($primary_keys != "") {
            $this->TableQuery .= ", PRIMARY KEY ($primary_keys)";
        }
        $this->TableQuery .= " )";
        return $this->TableQuery;
    }
}