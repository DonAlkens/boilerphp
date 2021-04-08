<?php

use App\Core\Urls\Request;

if(!function_exists("request"))
{
    /**
     * Instantiating request class for app 
     * view 
     * @return App\Core\Urls\Request;
     * */ 

    function request() {

        $method = $_SERVER["REQUEST_METHOD"];
        return new Request($method);
    }
}

if(!function_exists("url")) 
{
    /**
     * Gets the full url of the page
     * @return string;
     * */ 

    function url()
    {
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) 
            ||  isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $protocol = 'https://';
        }
        else 
        {
            $protocol = 'http://';
        }
        return $protocol.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    }
}

if(!function_exists("domain")) 
{
    /**
     * Gets the full domain of the page
     * @return string;
     * */ 

    function domain()
    {
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) 
            ||  isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $protocol = 'https://';
        }
        else 
        {
            $protocol = 'http://';
        }
        return $protocol.$_SERVER["HTTP_HOST"];
    }
}