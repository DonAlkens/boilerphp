<?php

namespace App\Core\Urls;



class Validator 
{

    /**
    * validation status
    *
    * @var string
    */
    public $validation = false;

    /**
    * validations messages
    *
    * @var array
    */
    protected $validation_messages = array();


    /**
     * Clears all previous validations logs
     *  
     * @return void
     * */

    public function clearValidationLog()
    {
        if(isset($_SESSION["request_validation_message"]))
        {
            unset($_SESSION["request_validation_message"]);
        }
    }

    /**
     * validates all required fields that are supplied
     *  
     * @param array fields
     * @return bool
     * */
    public function required($fields) 
    {
        $this->clearValidationLog();

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
            else 
            {
                $this->validationMessage($key, $key." is required!");
                return;
            }
        }

        if(count($this->validation_messages) > 0) 
        {
            $this->setValidationLog();
            $this->validation = false;
        } 
        else 
        {
            $this->validation = true;
        }
        
        return $this->validation;
    }


    /**
     * sets new validations logs using validations messages
     *  
     * @return void
     * */
    public function setValidationLog()
    {
        if(isset($_SESSION["request_validation_message"]))
        {
            $this->clearValidationLog();
        }

        $_SESSION["request_validation_message"] = $this->validation_messages;
    }


    /**
     * validates field data types
     *  
     * @param string property
     * @param string field
     * 
     * @return void
     * */
    public function validatePropType($prop, $field) 
    {

        if($this->$field == null || empty($this->$field))
        {
            $this->validationMessage($field, $field." is required!");
            return;
        }

        else if($prop == "integer")
        {
            if(!filter_var($this->$field, FILTER_VALIDATE_INT))
            {
                $this->validationMessage($field, "Only numbers are allowed in ".$field." field");
            }
        }

        else if($prop == "string") 
        {
            if(gettype($this->$field) != $prop) 
            {
                $this->validationMessage($field, "Invalid characters for field ".$field);
            }
        }

        else if($prop == "email")
        {
            if(!filter_var($this->$field, FILTER_VALIDATE_EMAIL))
            {
                $this->validationMessage($field, "Invalid email address");
            }
        }

        else if(strpos($prop, ":"))
        {
            $this->lengthValidation($prop,  $field);
        }

        else if($prop == "array") 
        {
            if($this->$field[0] == "" || $this->$field[0] == null)
            {
                $this->validationMessage($field, "Add at least one $field");
            }
        }

    }

    /**
     * validates data length
     *  
     * @param string property
     * @param string field
     * 
     * @return void
     * */
    public function lengthValidation($prop, $field)
    {
        $e = explode(":", $prop);
        $operator = $e[0];
        $length = $e[1];

        $operation = strlen( (string) $this->$field)." $operator ". $length;

        $result = true;

        eval("?>".'<?php $result = '.$operation."; ?>");
        
        if(!$result)
        {
            $this->validationMessage($field, "$field must be up to $length characters.");
        }
    }


    /**
     * creates validation messages
     *  
     * @param string field
     * @param string message
     * 
     * @return void
     * */
    public function validationMessage($field, $message)
    {
        $this->validation_messages[$field] = $message; 
    }


    /**
     * process validation properties
     *  
     * @param string validations
     * 
     * @return array|mixed
     * */
    public function validationProperties($validation) 
    {

        if(strpos($validation, "|")) 
        {
            $properties = explode("|", $validation);
        }
        else 
        {
            $properties = array($validation);
        }

        return $properties;
    }

}