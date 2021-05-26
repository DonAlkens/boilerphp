<?php

namespace App\Core\Database\Migration;

use App\Core\Database\DataTypes;

class ColumnDefination extends DataTypes {
    

    public function column($name) 
    {
        $this->column = $name; return $this;
    }

    /**
     * Declearing the colum name
     * @param $name 
     * @deprecated 
     * 
     * @return $this
     */
    public function field($name) 
    {
        $this->column = $name; return $this;
    }
    
    public function addColumn($name) {

        // Using ColumnDiagram

    }

    public function timestamps() {

        $this->column("created_date")->timestamp()->default("CURRENT_TIMESTAMP()");
        $this->column("updated_date")->timestamp()->default("CURRENT_TIMESTAMP()");

    }
}