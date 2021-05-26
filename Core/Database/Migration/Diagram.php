<?php

namespace App\Core\Database\Migration;


class Diagram extends ColumnDefination {

    public $columns = array();

    public function __construct($table, $query = "", $primary_keys = "")
    {
        $this->table = $table;
        return $this->createTableQuery($table, $query, $primary_keys);
    }
    
    public function createTableQuery($table, $query, $primary_keys) 
    {

        $this->TableQuery = "CREATE TABLE IF NOT EXISTS `$table` ($query";
        if($primary_keys != "") 
        {
            $this->TableQuery .= ", PRIMARY KEY ($primary_keys)";
        }
        
        $this->TableQuery .= " )";
        return $this->TableQuery;
    }
   
    public function dropTableQuery()
    {
        return $this->TableQuery = "SET FOREIGN_KEY_CHECKS = 1; DROP TABLE IF EXISTS `$this->table`; SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS `$this->table`;";
    }

}