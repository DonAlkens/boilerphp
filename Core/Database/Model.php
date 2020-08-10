<?php

namespace App\Core\Database;



class Model extends Relations {

    /**
    * Database Schema table name
    *
    * @var string
    *
    */
    public $table;

    public function __construct()
    { 
        $this->useTable();
        parent::__construct();
    }

    public function useTable(){
        if($this->table == null) {
            $model = strtolower(get_class($this));
            $model_class = explode("\\", $model);
            $this->table = end($model_class)."s";
        }
    }

}