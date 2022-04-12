<?php

namespace App\Core\Database\Migration;

use App\Core\Database\Schema;

class Migration extends Schema {

    public function registerMigration(array $data)
    {

        if($data) 
        {
            if($this->insertQuery($data)) 
            {
                $this->connect();

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
                $this->connect();

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