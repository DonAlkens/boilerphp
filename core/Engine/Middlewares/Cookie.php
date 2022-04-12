<?php

use App\Config\App;

class Cookie extends App
{

    public static function create($name, $value, $duration = 172800, $path = "/", $domain = null)
    {
        if($domain != null)
        {
            setcookie($name, $value, time() + $duration, $path, $domain);
        }
        else 
        {
            setcookie($name, $value, time() + $duration, $path);
        }
    }

    public static function clear()
    {
        unset($_COOKIE);
    }

    public static function fetch($name)
    {
        if(isset($_COOKIE[$name]))
        {
            $cookie = $_COOKIE[$name];
            if($cookie != "")
            {
                return $cookie;
            }
        }

        return null;
    }

    public static function expire($name, $time = 172800,$domain = "")
    {
        setcookie($name, "", time() - $time, "/", $domain);
        // unset($_COOKIE[$name]);
    }

    public static function update($name, $value)
    {
        $_COOKIE[$name] = $value;
    }

}