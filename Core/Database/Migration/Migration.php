<?php

namespace App\Core\Database;


class Migration extends Schema {

    public function table($name) 
    {
        $this->table = $name;
    }

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

    public function sign() {
        
        $diagram = new Diagram($this->table, 
            $this->trimmer($this->query), 
            $this->trimmer($this->primary_keys)
        ); 

        $this->foreignKeyProccessor($this->table);
        return $this->run($diagram->TableQuery);
    }

    public function dropIfExists($table)
    {

    }

    public function registerMigration(array $data)
    {

        if($data) 
        {
            if($this->insertQuery($data)) 
            {
                $statement = $this->connection->prepare($this->queryString);

                if($statement->execute($data))
                {
                    return true;
                }

                return null;
            }

        }

        return false;
    }

    public function checkMigrationExists(array $data)
    {

        if($data) 
        {
            if($this->insertQuery($data)) 
            {
                $statement = $this->connection->prepare($this->queryString);

                if($statement->execute($data))
                {
                    return true;
                }

                return null;
            }

        }

        return false;
    }
    
}