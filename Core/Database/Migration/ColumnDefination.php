<?php

namespace App\Core\Database\Migration;

use App\Core\Database\DataTypes;
use App\Core\Database\Schema;

class ColumnDefination extends DataTypes {
    

    public function column($name) {

        $this->column = "`$name`"; 
        return $this;

    }

    /**
     * Declaring the column name
     * 
     * @param $name 
     * @deprecated 
     * 
     * @return $this
     */
    public function field($name) {

        $this->column = "`$name`"; 
        return $this;

    }

    public function after($column) {

        $this->query = concat([$this->trimmer($this->query), "AFTER",  "`$column`"]);

    } 
    
    public function addColumn($name) {

        $this->column = concat(["ADD", "`$name`"]);
        return $this;

    }

    public function changeColumnName($current_name, $new_name) {

        $this->column = concat(["CHANGE", "`$current_name`", "`$new_name`"]);
        return $this;

    }

    public function dropColumn($name) {

        (new Schema)->run(concat(["ALTER TABLE `$this->table` DROP", "`$name`"]));
    }

    public function timestamps() {

        $this->column("created_date")->timestamp()->default("CURRENT_TIMESTAMP()");
        $this->column("updated_date")->timestamp()->default("CURRENT_TIMESTAMP()");

    }
}