<?php

use App\Config\ViewsConfig;


if(!function_exists("env")) 
{
    /** 
     * returns and enviroment variable if exists
     * 
     * @param string 
     * @return string
    */
    function env($key)
    {
        if(isset($_ENV))
        {
            return $_ENV[$key];
        }
    }
}



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
    function validation($key = "all")
    {
        if($key == "all") 
        {
            foreach($_SESSION["request_validation_message"] as $field => $message)
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
                    echo Session::get("request_validation_message")[$key];
                }

                unset(Session::get("request_validation_message")[$key]);
            }
        }

        
    }
}


if(!function_exists("route")) 
{
    /** 
     * returns a url string
     * 
     * @param string $path
     * @param string $params
     * @return string
    */

    function route($path, $paramters = null)
    {
        if($paramters != null) {
            foreach($paramters as $param) {
                $path .= "/".$param;
            }
        }
        return $path;
    }
}