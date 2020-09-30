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
        $this->map($_GET);
    }

    public function post() 
    {
        $this->map($_POST);
    }

    public function file($index)
    {
        if(isset($_FILES[$index]))
        {

            $file = $_FILES[$index];

        }
        else 
        {

            $file = null;

        }

        return $file;
    }

    public function filename($index)
    {
        if($this->file($index) != null) 
        {

            return $_FILES[$index]["name"];
            
        }
        else 
        {
            return null;
        }
    }

    public function map($data) 
    {
        
        foreach ($data as $key => $value) 
        {
            $this->$key = $value;
        }
    }

    public function timestamp() 
    {
        return date("Y-m-d H:i:s");
    }

    public function location() 
    {
        return $uri = trim($_SERVER["REQUEST_URI"],"/");
    }

}
