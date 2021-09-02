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
    * request location
    *
    * @var string
    */
    public $location;

    /**
    * request url
    *
    * @var string
    */
    public $url;

    /**
    * request url
    *
    * @var object
    */
    public $json;


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
        $this->location();
        
    }

    public function init($method)
    {
        $this->setHeaders();

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

    public function setHeaders() {

        foreach(getallheaders() as $name => $value) {
            $this->headers[$name] = $value;
        }
        
    }

    public function json() 
    {
        $data = json_decode(file_get_contents("php://input"), true);
        return $this->json = $data;
    }

    public function all()
    {
        $data = [];
        if($this->method == 'GET')
        {
            $data = $_GET;
        }
        else if($this->method == 'POST')
        {
            $data = $_POST;
        }

        return $data;
    }

    public function without($keys)
    {
        $all = $this->all();
        foreach($keys as $key) 
        {
            unset($all[$key]);
        }

        return $all;
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
            if($file["name"] == "")
            {
                $file = null;
            }

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

        $this->json();
    }

    public function timestamp() 
    {
        return date("Y-m-d H:i:s");
    }

    public function location() 
    {
        return $this->location = trim($_SERVER["REQUEST_URI"],"/");
    }

    public function url($name = null) 
    {
        $this->url = $this->location();

        if($name != null) 
        {
            if($this->url == $name) 
            {
                return true;
            }
            else 
            {
                return false;
            }
        }
        else 
        {
            return $this->url;
        }
    }

}
