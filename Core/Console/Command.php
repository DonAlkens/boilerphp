<?php


namespace Console;

use Console\Support\Actions;

class Command extends Actions {

    /*
    * ---------------------------------------- 
    * Start Server using command line manager
    * ----------------------------------------
    */
    public function start(...$parameters) 
    {
        $port = isset($parameters[0][0]) ? $parameters[0][0] : 8000;
        $this->runServer($port);
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
    public function db(...$parameters) 
    {
        $action = isset($parameters[0][0]) ? $parameters[0][0] : null;
        $flag = isset($parameters[0][1]) ? $parameters[0][1] : null; 

        if($action != null) 
        {
            if($flag != null) 
            {
                if(array_key_exists($flag, $this->db_flags)) 
                {
                    $this->$action($flag);
                }
            }
            else
            {
                $this->$action();
            }
        }

    }


    /*
    * --------------------------------------------------------------
    * Performs activation activities 
    * including third party libraries.
    * -------------------------------------------------------------
    */
    public function activate(...$parameters)
    {
        $action = isset($parameters[0][0]) ? $parameters[0][0] : null;
        $flag = isset($parameters[0][1]) ? $parameters[0][1] : null; 

        if($action != null) 
        {
            $action = str_replace("-", "", $action);

            if($flag != null)
            {
                if(array_key_exists($flag, $this->db_flags)) 
                {
                    $this->$action(true, $flag);
                }
            }
            else
            {
                $this->$action(true);
            }
        }
    }


    /*
    * --------------------------------------------------------------
    * disable libraries and activities 
    * remove unwanted configurations.
    * -------------------------------------------------------------
    */
    public function disable(...$parameters)
    {
        $action = isset($parameters[0][0]) ? $parameters[0][0] : null;
        $flag = isset($parameters[0][1]) ? $parameters[0][1] : null; 

        if($action != null) 
        {
            $action = str_replace("-", "", $action);

            if($flag != null)
            {
                if(array_key_exists($flag, $this->db_flags)) 
                {
                    $this->$action(false, $flag);
                }
            }
            else
            {
                $this->$action(false);
            }
        }
    }
    
}