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

    public function hasOne($model, $key) {

        $this->class = new $model;
        $this->class->relating = $model;
        $this->class->props = $this;


        $value_key = $key;
        $foreign_key = $key;

        if (is_array($key)) {
            foreach ($key as $_key => $_value) {
                # code...
                $foreign_key = $_key;
                $value_key = $_value;
            }
        }
        
        if($this->class->props->$value_key) {
            $this->value = $this->extractValue($this->class->props, $value_key);
            $this->class->useKey = $foreign_key;
            $this->class->select()->where([$this->class->useKey => $this->value]);
            return $this->class;
        }

        

    }

    public function hasMultiple($model, $key){

        $this->class = new $model;
        $this->class->relating = $model;
        $this->class->props = $this;
        
        $value_key = $key;
        $foreign_key = $key;

        if (is_array($key)) {
            foreach ($key as $_key => $_value) {
                # code...
                $foreign_key = $_key;
                $value_key = $_value;
            }
        }
        
        if($this->class->props->$value_key) {
            $this->value = $this->extractValue($this->class->props, $value_key);
            $this->class->useKey = $foreign_key;
            $objects = $this->class->selectAll()->where([$this->class->useKey => $this->value]);
            return $objects;
        }
    }


    public function merge($model, $table_name = null, $other_fields = null) {
        
        $class_name = get_class($this);
        $this->class_id_field = strtolower($class_name)."_"."id";
        $table = $this->table;

        
        $this->class = new $model;
        $this->model_name = get_class($this->class);
        $this->lower_case_class_name = strtolower($this->model_name);
        $this->model_id_field = strtolower($this->model_name)."_"."id";
        $this->model_table = $this->class->table;

        $this->_table = ($table_name != null) ? $table_name : $table."_".$this->model_table;

        $_structure = [
            "id" => "bigIncrements",
            $this->class_id_field => "integer",
            $this->model_id_field => "integer",
        ];

        if(!is_null($other_fields)) {
            $_structure = array_merge($_structure, $other_fields);
        }


        $this->table($this->_table, $_structure);
        $this->save();

        return $this;

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
        $result = $this->selectAll()->where([$this->class_id_field => $this->$key]);

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