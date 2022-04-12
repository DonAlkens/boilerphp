<?php

namespace App\Core\Database;

class Relations extends Schema {

    public $useKey;

    public function __construct()
    {
        parent::__construct();
    }

    public function createOne($data)
    {
        $data[$this->useKey] = $this->extractValue($this->props, $this->useKey);
        if($this->insert($data))
        {
            $this->success = true;
        }

        return $this;
    }

    public function createMultiple($collection)
    {
        foreach($collection as $data) {
            $this->createOne($data);
        }
    }

    public function hasOne($model, $key = null) 
    {
        if($this->setModelProperties($model)) 
        {
            if($this->setKeys($key)) 
            {
                $class = new $model;
                $value_key = $this->value_key;

                return $class->where($this->foreign_key, $this->$value_key)->attachClass();
            }
        }
    }

    public function hasMultiple($model, $key = null)
    {
        if($this->setModelProperties($model)) 
        {
            if($this->setKeys($key)) 
            {
                $class = new $model;
                $value_key = $this->value_key;
                
                return $class->where($this->foreign_key, $this->$value_key)->attachClass(true);
            }
        }
    }

    public function append($data) 
    {
        $this->table = $this->_table;

        $key = $this->useKey;
        $data[$this->class_id_field] = $this->$key;

        $this->insert($data);

        return $this;
    }

    protected function extractValue($object, $foreign_key) 
    {
        return $object->$foreign_key;
    }

    protected function getRelationsName() 
    {
        return $this->name;
    }

    protected function setModelProperties($model) 
    {
        if($model) 
        {

            $split_ = explode("\\", strtolower($model));

            $this->namespace = $split_[0];
            $this->class = $split_[1];

            return true;
        }

        return false;
    }

    protected function setKeys($key)
    {

        if(!is_null($key)) 
        {
            $this->value_key = $key;
            $this->foreign_key = $key;

            if (is_array($key)) 
            {
                foreach ($key as $_key => $_value) 
                {
                    $this->foreign_key = $_key;
                    $this->value_key = $_value;
                }

            }
        }
        else 
        {
            $modelClassNameSpace = get_class($this);
            $modelClassName = explode("\\", $modelClassNameSpace);

            $this->foreign_key = strtolower(end($modelClassName))."_id";
            $this->value_key = "id";
        }

        return true;

    }

    protected function pickAll() {

        $this->table = $this->_table;

        $key = $this->useKey;
        $result = $this->where($this->class_id_field, $this->$key)->get();

        $lower_case_class_name = $this->lower_case_class_name;
        $new_result = [];

        foreach ($result as $value) 
        {
            # code...
            $value->$lower_case_class_name = new $this->model_name;
            $key = $this->useKey;

            $row = $this->query("SELECT * FROM ". $this->model_table ." WHERE ".$key." = ".$value->$key);
            foreach ($row as $field => $dt) 
            {
                $value->$lower_case_class_name->$field = $dt;
            }

            array_push($new_result, $value);
        }

        return $new_result;
    }

}