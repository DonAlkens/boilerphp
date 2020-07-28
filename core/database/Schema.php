<?php

namespace App\Core\Database;

use Exception;

class Schema extends Connection
{


    public function __construct()
    {
        parent::__construct();
    }

    public function all()
    {
        $this->queryString = "SELECT * FROM $this->table";
        $statement = $this->connection->prepare($this->queryString);
        if($statement->execute()) {
            if($statement->rowCount() > 0) {
                return $this->resultFormatter($statement->fetchAll(), $multiple = true);
            }
        }
        return null;
    }

    public function first($key, $value) 
    {
        $this->result = $this->select($key, $value);
        if($this->resultTypeChecker($this->result) == "object") {
            return $this->result;
        }

        return array_shift($this->result);
    }

    public function insert(array $data)
    {
        if($data) {
            if($this->insertQuery($data)) {
                $statement = $this->connection->prepare($this->queryString);
                if($statement->execute($data)){
                    return $this->select($data);
                }
            }
        }
        return false;
    }

    public function last($key, $value) 
    {
        $this->result = $this->select($key, $value);
        return array_pop($result);
    }

    public function orderBy($key, $order) {
        $this->queryString .= $this->orderQuery($key, $order);
        return $this;
    }

    public function select($data, $value = null)
    {
        $this->value = $value;
        if($this->dataFormatChecker($data, $value)) {
            if($this->selectQuery($this->data)){
                $statement = $this->connection->prepare($this->queryString);
                if($statement->execute($this->data)){
                    if($statement->rowCount() > 0) {
                        return ($statement->rowCount() > 1)  
                        ? $this->resultFormatter($statement->fetchAll(), $multiple = true) 
                        : $this->resultFormatter($statement->fetch());
                    }
                }
            }
        }
        return null;
    }

    public function where($data, $value = null) {
        $this->value = $value;
        if($this->dataFormatChecker($data, $value)) {
            if($this->selectQuery($this->data)){
                return $this;
            }
        }
        return null;
    }

    public function get() {
        $statement = $this->connection->prepare($this->queryString);
        if($statement->execute($this->data)){
            if($statement->rowCount() > 0) {
                return ($statement->rowCount() > 1)  
                ? $this->resultFormatter($statement->fetchAll(), $multiple = true) 
                : $this->resultFormatter($statement->fetch());
            }
        }
    }

    
    // public function update($data)
    // {
    //     if (is_array($data)) {
    //         $this->queryMode = "run";
    //         $this->update_map($data);
    //     }
    //     return $this;
    // }
    
    // public function delete()
    // {
    //     $this->queryMode = "run";
    //     $this->delete_map();
    //     return $this;
    // }

    public function resultFormatter($result, $multiple = false) 
    {
        $data = [];
        $class = get_class($this);

        if($multiple == true) {
            foreach ($result as $instance) {
                $class = $this->newObject($class, $instance);
                array_push($data, $class);
            }

            return $data;
        }

        return $this->newObject($class, $result);
    }

    public function newObject($name, $instance) {
        $class = new $name;
        foreach ($instance as $key => $value) {
            $class->$key = $value;
        }
        return $class;
    }


    public function run($query)
    {
        if (!empty($query)) {

            try {
                $statement = $this->connection->prepare($query);
                if ($statement->execute()) {
                    return true;
                }
            } catch (Exception $e) {
                echo "Unable to run query: $query ; $e";
                return false;
            }
        }
    }

    public function save()
    {
        if ($this->connection != null) {
            if ($this->connection->query($this->query)) {
                return true;
            }
            return false;
        }
    }
    
    public function query($querystring, $data = null)
    {
        if ($querystring !== "") {
            $statement = $this->connection->prepare($querystring);
            
            if($data != null) {
                if($statement->execute($data)){
                    return $statement;
                }
            } else {
                if($statement->execute()){
                    return $statement;
                }
            }
        }
        return null;
    }

}

// first([$key], [$value])
// last([$key], [$value])
// where([$key], [$value])
// orderBy([$key], [$value])
