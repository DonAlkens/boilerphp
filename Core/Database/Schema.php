<?php

namespace App\Core\Database;

use Exception;
use ReflectionClass;
use ReflectionMethod;

class Schema extends Connection
{

    /**
    * Query formated by query builder
    *
    * @var string
    *
    */
    public $queryString = "";

    /**
    * Database Schema table name
    *
    * @var string
    *
    */
    public $table;


    public function __construct()
    {
        parent::__construct();
        $this->clearInitalQuery();
        $this->useTable();
    }

    public function attach($all = false, $fields = null) {

        if($all == true) 
        {
            $this->allQuery();
            $data = $this->fetch(true);

            if(is_null($data)) 
            {
                return $data;
            }

            if(!is_array($data)) 
            { 
                return array($data); 
            } 
            else 
            {
                return $data;
            }
        }
        else 
        {

            if($this->fieldFormatChecker($fields)) 
            {
                if($this->selectQuery($this->fields))
                {
                    return $this->fetch(true);
                }
            }
        }

    }

    public function useTable()
    {
        if($this->table == null) 
        {
            $namespace = explode("\\", get_class($this));
            $namespace = str_split(end($namespace));

            $format = "";
            foreach($namespace as $key => $char) 
            {
                if(ctype_upper($char)) 
                {
                    $format .= "_".strtolower($char); 
                    continue;
                }

                $format .= $char;
            }

            $table = trim($format, "_");
            $lastchar = strtolower(substr($table, -1));
            if($lastchar != "s") { $table .= "s"; }

            $this->table = $table;
        }
    }

    public function positionCollection($key, $value) 
    {

        if(!is_null($key) && !is_null($value))
        { 

            $result =  $this->where($key, $value)->select();

        } 
        else 
        {

            $result = $this->select();

        }

        return $result;
    }

    public function all()
    {

        $this->allQuery();
        $data = $this->fetch();

        if(is_null($data)) 
        {
            return $data;
        }

        if(!is_array($data)) 
        { 
            return array($data); 
        } 
        else 
        {
            return $data;
        }

    }

    public function bootRelations($class)
    {
        $class_name = get_class($class);

        $clReflection = new ReflectionClass($class_name);
        foreach($clReflection->getMethods() as $clMethod) {
            if($clMethod->class == $class_name) {
                $method = $clMethod->name;
                if($method != '__construct')
                {
                    $mReflection = new ReflectionMethod($class_name, $method);
                    $params = $mReflection->getParameters();

                    if(count($params) == 0)
                    {
                        $result = $class->{$method}();
                        $instance = $result;
                        if(is_array($result)) { $instance = $result[0]; }
                        if($instance instanceof Model) 
                        {
                            $class->{$method} = $result;
                        }
                    }
                }
            }
        }
    }

    public function count() 
    {
        if(!is_null($this->all())) {
            return count($this->all());
        }
        else 
        {
            return 0;
        }
    }

    public function clearInitalQuery()
    {
        $this->queryString = "";
        $this->whereQuery = "";
        $this->orderQuery = "";
        $this->groupQuery = "";
    }

    public function first($key = null, $value = null) 
    {

        $result = $this->positionCollection($key, $value);

        if($this->resultTypeChecker($result) == "object") 
        {

            return $result;

        }

        if($result != null) {

            return array_shift($this->result);
        }

        return null;

    }

    public function last($key = null, $value = null) 
    {

        $result = $this->positionCollection($key, $value);

        if($this->resultTypeChecker($result) == "object") 
        {

            return $result;

        }

        if($result != null) {
            return array_pop($result);
        }

        return null;

    }

    public function find($key, $value = null) 
    {

        if($value == null) 
        {
            $this->result = $this->where("id", $key)->select();
        }
        else 
        {
            $this->result = $this->where($key, $value)->select();
        }

        if($this->result !== null) 
        {

            return $this->result;

        }

        return null;

    }

    public function groupBy($column) 
    {

        $this->groupQuery($column);
        return $this;

    }

    public function orderBy($key, $order = "ASC", $limit = null) 
    {

        $this->orderQuery($key, $order, $limit);
        return $this;

    }

    public function sum($column) 
    {
        $sum = 0;
        $data = $this->all();
        if($data) 
        {
            foreach($data as $row) {
                $sum += $row->$column;
            }
        }

        return $sum;
    }

    public function insert(array $data)
    {

        if($data) 
        {

            if($this->insertQuery($data)) 
            {

                $statement = $this->connection->prepare($this->queryString);

                
                if($statement->execute($data))
                {
                    $exec = $this->query("SELECT * FROM $this->table WHERE id = LAST_INSERT_ID()");
                    if($exec)
                    {
                        if($exec->rowCount() > 0) 
                        {
                            return $this->resultFormatter($exec->fetch());
                        }
                    }

                }

                return null;
            }

        }

        return false;
    }

    public function select($fields = null)
    {

        if($this->fieldFormatChecker($fields)) 
        {

            if($this->selectQuery($this->fields))
            {
                return $this->fetch();
            }

        }

        return null;
    }
    
    public function update($data, $value = null)
    {

        if($this->dataFormatChecker($data, $value)) 
        {
            if($this->updateQuery($this->data)) 
            {
                if(empty($this->whereQuery))
                {
                    return $this->where("id", $this->id)->update($this->data);
                }
                else
                {
                    if($this->save())
                    {
                        if(isset($this->id))
                        {
                            return $this->find($this->id);
                        }

                        return true;
                    }
                }
            }
        }
        else
        {
            return false;
        }

        return true;
    }
    
    public function delete($key = null, $value = null)
    {

        if(is_null($key) && is_null($value))
        {
            if(isset($this->id)) 
            {
                $key = "id"; $value = $this->id;
            }
            else 
            {
                // throwable error for parameter expected
            }
        }
        elseif(is_null($value)) 
        { 
            $value = $key; 
            $key = "id"; 
        }

        if($this->dataFormatChecker($key, $value)) 
        {

            if($this->deleteQuery($this->data))
            {

                $statement = $this->connection->prepare($this->queryString);

                if($statement->execute($this->whereData))
                {
                    return true;
                }

            }

        }

        return false;
    }

    public function search($keys, $value = null, $opration = ['%', '%']) 
    {
        if(is_array($keys))
        {
            if($value != null && is_array($value)) {
                $opration = $value;
            }
        }

        $this->searchQuery($keys, $value, $opration);
        return $this;

    }

    public function where($keys, $value = null) 
    {

        $this->whereQuery($keys, $value);
        return $this;

    }

    public function whereWithOperation($keys, $opration, $value = null) 
    {

        $this->whereQuery($keys, $value, $opration);
        return $this;

    }

    public function get() 
    {

        return $this->select();

    }

    public function resultFormatter($result, $multiple = false, $relations = false) 
    {

        $data = [];
        $class = get_class($this);

        if($multiple == true) 
        {

            foreach ($result as $instance) 
            {

                $class = $this->newObject($class, $instance, $relations);
                array_push($data, $class);

            }

            return $data;

        }

        return $this->newObject($class, $result, $relations);

    }

    public function newObject($name, $instance, $relations = false) 
    {

        $class = new $name;

        foreach ($instance as $key => $value) 
        {
            $class->$key = $value;
        }

        if($relations == false) {
            $this->bootRelations($class);
        }

        return $class;

    }


    public function fetch($relations = false)
    {

        if($this->queryString()) 
        {

            $statement = $this->connection->prepare($this->queryString);

            (isset($this->whereData))
            ? $exec = $statement->execute($this->whereData)
            : $exec = $statement->execute();

            if($exec)
            {

                if($statement->rowCount() > 0) 
                {

                    return ($statement->rowCount() > 1)  
                    ? $this->resultFormatter($statement->fetchAll(), true, $relations) 
                    : $this->resultFormatter($statement->fetch(), false, $relations);
                    
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

        if ($this->connection != null && $this->queryString()) 
        {

            $statement = $this->connection->prepare($this->queryString);

            (isset($this->whereData))
            ? $exec = $statement->execute($this->whereData)
            : $exec = $statement->execute();

            if($exec)
            {
                return true;
            }

            return false;

        }

    }

    public function setTable($name)
    {
        $this->table = $name;
        return $this;
    }
    
    public function query($querystring, $data = null)
    {

        if ($querystring !== "") 
        {

            $statement = $this->connection->prepare($querystring);
            
            if($data != null) 
            {

                if($statement->execute($data))
                {
                    return $statement;
                }
            } 
            
            else 
            
            {

                if($statement->execute())
                {
                    return $statement;
                }

            }
        }

        return null;

    }

}
