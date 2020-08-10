<?php

namespace App\Core\Urls;

class Request extends Validator 
{

    /**
    * url parameters 
    *
    * @var string
    */
    public $param;

    /**
    * request method
    *
    * @var string
    */
    public $method;


    /**
    * set the method use in http request
    *
    * @param string method of http request action
    * @return void
    */
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

}
