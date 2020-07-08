<?php


namespace Console;

class Command {

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
        $name = null;
    }

    
}