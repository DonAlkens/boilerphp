<?php

namespace App\Core\Urls;

class Request {

    public $param;

    public $method;

    public $validationMessages = array();

    public function __construct($method) 
    {
        $this->method = strtoupper($method);
        $this->init($method);
    }

    public function init($method)
    {

        switch($method)
        {
            case 'get':
                $this->get();
            break;
            
            case 'post':
                $this->post();
             break;
        }

        foreach ($_FILES as $key => $value) 
        {
            $this->$key = $value;
        }
    }

    public function get() 
    {
        $object = $_GET;
        $this->result = $object;
        $this->map($object);
    }

    public function post() 
    {
        $object = $_POST;
        $this->result = $object;
        $this->map($object);
    }

    public function file($index)
    {
        $file = $_FILES[$index];
        return $file;
    }

    public function filename($index)
    {
        $file_name = $_FILES[$index]["name"];
        return $file_name;
    }

    public function map($data) 
    {
        
        foreach ($data as $key => $value) 
        {
            $this->$key = $value;
        }
    }

    public function fieldValidator($fields) 
    {

        foreach($fields as $key => $validation) 
        {
            if(isset($this->$key)) 
            {
                $props = $this->validationProperties($validation);
                foreach($props as $prop) 
                {
                    $this->validatePropType($prop, $key);
                }
            }
        }

        if(count($this->validationMessages)) {
            $this->validation = false;
            return false;
        }

        $this->validation = true;
        return true;
    }


    public function validatePropType($prop, $field) 
    {

        if($prop == "string" || $prop == "integer") 
        {
            if(gettype($this->$field) != $prop) 
            {
                $this->validationMessage($field, "Invalid characters for field ".$field);
            }
        }

        else if(strpos($prop, ":"))
        {
            $this->lengthValidation($prop,  $field);
        }
    }


    public function lengthValidation($prop, $field)
    {
        $e = explode(":", $prop);
        $operator = $e[0];
        $length = $e[1];

        $operation = strlen( (string) $this->$field)." $operator ". $length;
        
        if(!(int)($operation))
        {
            $this->validationMessage($field, "$field must be up to $length characters.");
        }
    }


    public function validationMessage($field, $message)
    {
        $this->validationMessages[$field] = $message; 
    }


    public function validationProperties($validation) 
    {

        $properties = $validation;
        if(strpos($validation, "|")) 
        {
            $properties = explode("|", $validation);
        }

        return $this->formatValidationProperties($properties);
    }


    public function formatValidationProperties($properties) 
    {

        if(is_string($properties)) 
        {
            return array($properties);
        }

        return $properties;
    }
}
