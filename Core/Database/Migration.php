<?php

namespace App\Core\Database;


class Migration extends Schema {

    public function table($name) 
    {
        $this->table = $name;
    }

    public function field($name) 
    {
        $this->column = $name; return $this;
    }

    public function sign() {
        
        $diagram = new Diagram($this->table, 
            $this->trimmer($this->query), 
            $this->trimmer($this->primary_keys)
        ); 

        $this->foreignKeyProccessor($this->table,);

        return $this->run($diagram->TableQuery);
    }

    public function dropIfExists($table)
    {

    }
    
}