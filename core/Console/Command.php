<?php


namespace Console;

use Console\Support\Actions;

require_once __DIR__."/src/Actions.php";

class Command extends Actions {

    /*
    * ---------------------------------------- 
    * Start Server using command line manager
    * ----------------------------------------
    */
    public function start(...$parameters) {

        $port = isset($parameters[0][0]) ? $parameters[0][0] : 8000;
        
        
    }

    public function create(...$parameters)
    {
        $action = isset($parameters[0][0]) ? $parameters[0][0] : null;
        $name = isset($parameters[0][1]) ? $parameters[0][1] : null; 

        if($action != null) {
            $this->$action($name);
        }
    }
    
}