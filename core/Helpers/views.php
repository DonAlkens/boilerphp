<?php

use App\Config\ViewsConfig;
use App\Core\Urls\Route;


if(!function_exists("load_static")) 
{
    /** 
     * returns path to a static file if exists
     * 
     * @param string $filesource
     * @return string $filepath
    */
    function load_static($filesource) 
    {
        if(file_exists(ViewsConfig::$static_files_path."/".$filesource)) 
        {
            $filepath = "/".ViewsConfig::$static_files_path."/".$filesource;
            return $filepath;
        }
    }
}


if(!function_exists("validation")) 
{
    /** 
     * returns a validation message set by request Validator
     * 
     * @param string $key
     * @return App\Core\Urls\Validator|string
    */
    function validation($key = "all", $message = null)
    {
        if($key == "all") 
        {
            foreach(Session::get("request_validation_message") as $field => $message)
            {
                echo "<span class=\"text-danger\">$message</span>\n";
            }

            Session::end("request_validation_message");

        }
        else
        {
            if(Session::get("request_validation_message"))
            {
                if(isset(Session::get("request_validation_message")[$key])) 
                {
                    if(!is_null($message))
                    {
                        echo $message;
                    }
                    else
                    {
                        echo Session::get("request_validation_message")[$key];
                    }
                }

                unset(Session::get("request_validation_message")[$key]);
            }
        }

        
    }
}