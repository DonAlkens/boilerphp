<?php 

class Session 
{

    static public function exists($name) 
    {
        if(isset($_SESSION[$name]))
        {
            return true;
        }
        return false;
    }

    static function set($name, $value) 
    {
        $_SESSION[$name] = $value;
    }

    static function end($name) 
    {
        unset($_SESSION[$name]);
    }

    static function get($name)
    {
        if(isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        } 

        return false;
    }

    static function endall()
    {
        $_SESSION == null;
        session_destroy();
    }
}

