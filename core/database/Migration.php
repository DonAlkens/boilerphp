<?php

namespace App\Core\Database;

use App\Core\Database\Diagram;

class Migration extends DataTypes {

    public function table($name) 
    {
        return new Diagram($name, 
                    $this->trimmer($this->query), 
                    $this->trimmer($this->primary_keys)); 
    }

    public function field($name) 
    {
        $this->column = $name; return $this;
    }

    public function dropIfExists($table)
    {

    }
    
}