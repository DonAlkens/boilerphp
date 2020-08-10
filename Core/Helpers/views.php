<?php

function env($key)
{
    return $_ENV[$key];
}

function loadStatic($filesource) 
{
    if(file_exists("public/".$filesource)) 
    {
        return "/public/".$filesource;
    }
}

function validation($key = "all")
{
    if($key == "all") 
    {
        foreach($_SESSION["request_validation_message"] as $field => $message)
        {
            echo "<span class=\"text-danger\">$message</span>\n";
        }

    }
    else
    {
        if(isset($_SESSION["request_validation_message"][$key]))
        {
            echo $_SESSION["request_validation_message"][$key];
        }
    }

    
}

function route($path, $paramters = null)
{
    if($paramters != null) {
        foreach($paramters as $param) {
            $path .= "/".$param;
        }
    }
    return $path;
}