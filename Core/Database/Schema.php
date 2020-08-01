<?php

namespace App\Core\Database;

use Exception;

class Schema extends Connection
{

    public $queryString = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function positionCollection($key, $value) {
        if(!is_null($key) && !is_null($value)){ 
            $result =  $this->where($key, $value)->select();
        } else {
            $result = $this->select();
        }

        return $result;
    }

    public function all()
    {
        $this->allQuery();
        return $this->fetch();
    }

    public function first($key = null, $value = null) 
    {
        $result = $this->positionCollection($key, $value);
        if($this->resultTypeChecker($result) == "object") {
            return $result;
        }
        return array_shift($this->result);
    }

    public function last($key = null, $value = null) 
    {
        $result = $this->positionCollection($key, $value);
        if($this->resultTypeChecker($result) == "object") {
            return $result;
        }
        return array_pop($result);
    }

    public function find($key, $value) 
    {
        $this->result = $this->select($key, $value);
        if($this->result !== null) {
            return $this->result;
        }

        return null;
    }

    public function groupBy($column) {
        $this->groupQuery($column);
        return $this;
    }

    public function orderBy($key, $order = "ASC") {
        $this->orderQuery($key, $order);
        return $this;
    }

    public function insert(array $data)
    {
        if($data) {
            if($this->insertQuery($data)) {
                $statement = $this->connection->prepare($this->queryString);
                if($statement->execute($data)){
                    return $this->where($data)->get();
                }
            }
        }
        return false;
    }

    public function select($fields = null)
    {
        if($this->fieldFormatChecker($fields)) {
            if($this->selectQuery($this->fields)){
                $this->queryString .= $this->whereQuery;
                return $this->fetch();
            }
        }
        return null;
    }
    
    public function update($data, $value = null)
    {
        if($this->dataFormatChecker($data, $value)) {
            if($this->updateQuery($this->data)) {
                $this->queryString .= $this->whereQuery;
                if($this->save()){
                    return $this->where($this->data)->get();
                }
            }
        }
        return false;
    }
    
    public function delete($key, $value = null)
    {
        if($this->dataFormatChecker($key, $value)) {
            if($this->deleteQuery($this->data)){
                $statement = $this->connection->prepare($this->queryString);
                if($statement->execute($this->whereData)){
                    return true;
                }
            }
        }

        return false;
    }

    public function where($keys, $value = null) 
    {
        $this->whereQuery($keys, $value);
        return $this;
    }

    public function get() {
        return $this->select();
    }

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


    public function fetch()
    {
        if($this->queryString()) {
            $statement = $this->connection->prepare($this->queryString());
            
            (isset($this->whereData))
            ? $exec = $statement->execute($this->whereData)
            : $exec = $statement->execute();

            if($exec){
                if($statement->rowCount() > 0) {
                    return ($statement->rowCount() > 1)  
                    ? $this->resultFormatter($statement->fetchAll(), $multiple = true) 
                    : $this->resultFormatter($statement->fetch());
                }

                return null;
            } 
        }
    }

    public function run($queryString)
    {
        $statement = $this->connection->prepare($queryString);
        if($statement->execute())
        {
            return true;
        }

        return false;
    }

    public function save()
    {
        if ($this->connection != null && $this->queryString()) {
            $statement = $this->connection->prepare($this->queryString());

            (isset($this->whereData))
            ? $exec = $statement->execute($this->whereData)
            : $exec = $statement->execute();

            if($exec){
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
