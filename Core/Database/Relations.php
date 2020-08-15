<?php

namespace App\Core\Database;

class Relations extends Schema {

    public $useKey;

    public function __construct()
    {
        parent::__construct();
    }

    public function create($data) {

        $data[$this->useKey] = $this->extractValue($this->props, $this->useKey);
        if($this->insert($data)){
            $this->success = true;
        }
        return $this;
    }

    public function extractValue($object, $foreign_key) {
        return $object->$foreign_key;
    }

    public function hasOne($model, $key) 
    {
        
        if($this->setModelProperties($model)) 
        {
            $name = $this->getRelationsName();
    
            if($this->setKeys($key)) 
            {
                $class = new $model;
                $value_key = $this->value_key;
                
                $this->result = $class->where($this->foreign_key, $this->$value_key)->select();

                return $this->result;
            }

        }

        // return OCI_RETURN_NULLS;
        
    }

    public function getRelationsName() {
        return $this->name;
    }

    public function setModelProperties($model) 
    {
        if($model) {

            $split_ = explode("\\", strtolower($model));

            $this->namespace = $split_[0];
            $this->class = $split_[1];

            return true;
        }
        return false;
    }

    public function setKeys($key)
    {
        $this->value_key = $key;
        $this->foreign_key = $key;

        if (is_array($key)) {
            foreach ($key as $_key => $_value) {
                $this->foreign_key = $_key;
                $this->value_key = $_value;
            }
        }

        return true;
    }

    public function hasMultiple($model, $key){
        return $this->hasOne($model, $key);
    }

    public function attach($data) {
        $this->table = $this->_table;

        $key = $this->useKey;
        $data[$this->class_id_field] = $this->$key;

        $this->insert($data);
        return $this;
    }

    public function key($name) {
        $this->useKey = $name;
        return $this;
    }

    public function pickAll() {
        $this->table = $this->_table;
        $key = $this->useKey;
        $result = $this->select($this->class_id_field, $this->$key);

        $lower_case_class_name = $this->lower_case_class_name;

        $new_result = [];
        foreach ($result as $value) {
            # code...
            $value->$lower_case_class_name = new $this->model_name;
            $key = $this->useKey;

            $row = $this->query("SELECT * FROM ". $this->model_table ." WHERE ".$key." = ".$value->$key);
            
            foreach ($row as $field => $dt) {
                $value->$lower_case_class_name->$field = $dt;
            }

            array_push($new_result, $value);
        }

        return $new_result;
    }

}