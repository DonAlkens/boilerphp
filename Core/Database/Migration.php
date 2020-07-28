<?php

namespace App\Core\Database;

use App\Core\Database\Diagram;

class Migration extends Schema {

    public function table($name) 
    {
        $diagram = new Diagram($name, 
                    $this->trimmer($this->query), 
                    $this->trimmer($this->primary_keys)); 

        return $this->run($diagram->TableQuery);
    }

    public function field($name) 
    {
        $this->column = $name; return $this;
    }

    public function dropIfExists($table)
    {

    }
    
}