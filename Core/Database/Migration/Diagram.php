<?php

namespace App\Core\Database\Migration;


class Diagram extends ColumnDefination {

    public $columns = array();

    public function __construct($table)
    {
        $this->table = $table;
    }
    
    public function createTableQuery($columns, $primary_keys) 
    {

        $this->TableQuery = "CREATE TABLE IF NOT EXISTS `$this->table` ($columns";
        if($primary_keys != "") {
            $this->TableQuery .= ", PRIMARY KEY ($primary_keys)";
        }
        
        $this->TableQuery .= " )";
        return $this->TableQuery;
    }

    public function modifyTableQuery($columns, $primary_keys) {

        $this->TableQuery = "ALTER TABLE `$this->table` $columns";
        if($primary_keys != "") { $this->TableQuery .= ", ADD PRIMARY KEY ($primary_keys)"; }
        return $this->TableQuery;
    } 

    public function setPkMode($mode) {
        $this->pk_mode = $mode;
    }
}