<?php

# namespace App\Core\Engine;

class Request {

    public $param;
    public $method;

    public function __construct($method) {
        $this->method = strtoupper($method);
        Request::init($method);
    }

    private function init($method){
        switch($method){
            case 'get':
                Request::res_for_get();
            break;
            
            case 'post':
                Request::res_for_post();
             break;
        }

        foreach ($_FILES as $key => $value) {
            $this->$key = $value;
        }
    }

    private function res_for_get() {
        $object = $_GET;
        $this->result = $object;
        Request::map($object);
    }

    private function res_for_post() {
        $object = $_POST;
        $this->result = $object;
        Request::map($object);
    }

    public function file($index){
        $file = $_FILES[$index];
        return $file;
    }

    public function filename($index){
        $file_name = $_FILES[$index]["name"];
        return $file_name;
    }

    public function map($data) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
}
