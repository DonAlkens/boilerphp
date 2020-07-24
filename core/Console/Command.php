<?php


namespace Console;

use Console\Support\Actions;

class Command extends Actions {

    /*
    * ---------------------------------------- 
    * Start Server using command line manager
    * ----------------------------------------
    */
    public function start(...$parameters) {

        $port = isset($parameters[0][0]) ? $parameters[0][0] : 8000;
        
    }

    /*
    * --------------------------------------------------------------
    * Create Project [Controller, Models, Migration, Notifications] 
    * using command line manager, files will be generated.
    * -------------------------------------------------------------
    */
    public function create(...$parameters)
    {
        $action = isset($parameters[0][0]) ? $parameters[0][0] : null;
        $name = isset($parameters[0][1]) ? $parameters[0][1] : null; 
        $flag = isset($parameters[0][2]) ? $parameters[0][2] : null;

        if($action != null) {
            if($flag != null) {
                if(array_key_exists($flag, $this->flags)) {
                    $this->$action($name, $flag);
                }
            } else {
                $this->$action($name);
            }
        }
    }


    /*
    * --------------------------------------------------------------
    * Performs Database [Migrations] actions 
    * using command line manager.
    * -------------------------------------------------------------
    */
    public function db(...$parameters) {

        $action = isset($parameters[0][0]) ? $parameters[0][0] : null;
        $name = isset($parameters[0][1]) ? $parameters[0][1] : null; 
        $flag = isset($parameters[0][2]) ? $parameters[0][2] : null;

        if($action != null) {
            if($flag != null) {
                if(array_key_exists($flag, $this->flags)) {
                    $action = "db_".$action;
                    $this->$action($name, $flag);
                }
            } else {
                $this->$action($name);
            }
        }

    }
    
}