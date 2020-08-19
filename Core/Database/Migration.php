<?php

namespace App\Core\Database;


class Migration extends Schema {

    public function table($name) 
    {
        $diagram = new Diagram($name, 
                    $this->trimmer($this->query), 
                    $this->trimmer($this->primary_keys)
                ); 

        $this->foreignKeyProccessor($name);

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