<?php

use App\Hashing\Hash;

if(!function_exists("dump"))
{
    /** 
     * dumps all request values
     * 
     * @param App\Core\Urls\Request $request;
     * @return Json;
    */

    function dump($request) 
    {
        Json($request->all());
        die();
    }
}

if(!function_exists("session"))
{
    /** 
     * returns app Session modules
     * 
     * @param string,null
     * @return Session;
    */

    function session($key = null) 
    {
        if(!is_null($key))
        {
            return Session::get($key);
        }

        return new Session;
    }
}

if(!function_exists("cookie"))
{
    /** 
     * returns app Session modules
     * 
     * @param string|null
     * @return Cookie;
    */

    function cookie($name = null) 
    {
        if(!is_null($name))
        {
            return Cookie::fetch($name);
        }

        return new Cookie;
    }
}


if(!function_exists("add_time"))
{
    /** 
     * Add time or days to an existings date 
     * 
     * @param string $datetime
     * @param string $no_of_days
     * @param string $format
     * @return string 
    */

    function add_time($datetime, $no_of_days, $format = "Y-m-d H:i:s") 
    {

        $date = date_create($datetime);
        date_add($date, date_interval_create_from_date_string($no_of_days));
        $result = date_format($date, $format);
    
        return $result;
    
    }
}

if(!function_exists("forcast"))
{
    /** 
     * Add time or days to an existings date 
     * 
     * @param int $interval
     * @param string $format
     * @return object 
    */

    function forecast($interval, $format = null, $actual_date = null) 
    {
        if($format == null)
        {
            $format = "Y-m-d H:i:s";
        }
        
        if($actual_date)
        {
            $date = date($format, strtotime($actual_date. "+$interval days"));
        }
        else 
        {
            $date = date($format, strtotime("+$interval days"));
        }
        
        $data["date"] = $date;
        
        $result = json_encode($data);
        return json_decode($result);
    }
}

if(!function_exists("reverse_date"))
{
    /** 
     * Add time or days to an existings date 
     * 
     * @param int $interval
     * @param string $format
     * @return object 
    */

    function reverse_date($interval, $format = null, $actual_date = null) 
    {
        if($format == null)
        {
            $format = "Y-m-d H:i:s";
        }
        
        if($actual_date)
        {
            $date = date($format, strtotime($actual_date. "-$interval days"));
        }
        else 
        {
            $date = date($format, strtotime("-$interval days"));
        }
        
        $data["date"] = $date;
        
        $result = json_encode($data);
        return json_decode($result);
    }
}

if(!function_exists("get_time_diff"))
{
    /** 
     * get diffrence in time and date 
     * 
     * @param string $datetime
     * @return object 
    */

    function get_time_diff($datetime) {

        $data = array(
            "days" => 0,
            "hours" => 0,
            "mins" => 0
        );
    
        $now = time();
        $to_time = strtotime($datetime);
    
        $f = $to_time - $now;
        $data["days"] = round($f /(60 * 60 * 24));
        $data["hours"] = round($f /(60 * 60));
        $data["mins"] = round($f /(60));
    
        $data = json_encode($data);
        return json_decode($data);
    
    }
}

if(!function_exists("format_date"))
{
    /** 
     * Format date with a giving format type 
     * 
     * @param string $date
     * @param string $format
     * @return string $formated_date
    */

    function format_date($date, $format = "Y-m-d H:i:s")
    {
        $date = date_create($date);
        return date_format($date, $format);
    }
}

if(!function_exists("timestamp"))
{
    /** 
     * Get the exact current datetime 
     * 
     * @param string $format
     * @return string $current_datetime
    */

    function timestamp($format = "Y-m-d H:i:s")
    {
        return date($format);
    }
}

if(!function_exists("flash"))
{
    /** 
     * Add time or days to an existings date 
     * 
     * @param string $key
     * @param string $message
    */

    function flash($key, $message = null) 
    {

        if($message != null) 
        {
            $flash = [$key => $message];
            if(Session::get("app_core_flash_messages"))
            {
                $flashs = Session::get("app_core_flash_messages");
                $flash = array_merge($flashs, $flash);
            }

            Session::set("app_core_flash_messages", $flash);
        }
        else 
        {
            if(Session::get("app_core_flash_messages"))
            {
                $flashs = Session::get("app_core_flash_messages");

                if(array_key_exists($key, $flashs))
                {
                    $message = $flashs[$key]; 
                    unset($flashs[$key]);
                    Session::set("app_core_flash_messages", $flashs);
                   
                    return $message;
                }
            }
        }
    
    }
}

if(!function_exists("flash_check"))
{
    /** 
     * Add time or days to an existings date 
     * 
     * @param string $key
    */

    function flash_check($key) 
    {
        if(Session::get("app_core_flash_messages"))
        {
            $flashs = Session::get("app_core_flash_messages");

            if(array_key_exists($key, $flashs))
            {
                return true;
            }
        }

        return false;
    }
}

if(!function_exists("concat"))
{
    /**
     * Combines string characters together 
     * 
     * @param array $stringlist
     * @param string $seprator
     * @return string
     */

    function concat(array $stringlist, string $seprator = " ") 
    {
        $concatenation = "";
        foreach($stringlist as $string)
        {
            if($string != "")
            {
                $concatenation .= $string.$seprator;
            }
        }

        return trim($concatenation, $seprator);
    }
}


if(!function_exists("is_mobile"))
{
    /**
     * checks if device is a mobile device 
     * 
     * @return int|false
     */
    function is_mobile() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
}

if(!function_exists("env")) 
{
    /** 
     * returns and enviroment variable if exists
     * 
     * @param string 
     * @return mixed
    */
    function env($key)
    {
        if(isset($_ENV))
        {
            return trim($_ENV[$key]);
        }
    }
}

if(!function_exists("setEnv")) 
{
    /** 
     * returns and enviroment variable if exists
     * 
     * @param string 
     * @return string
    */
    function setEnv($key, $value)
    {
        $_ENV[$key] = $value;
    }
}

if(!function_exists("hashing"))
{
    /** 
     * returns encrypted string value
     * 
     * @param $string 
     * @param $clean boolean
     * @return string
    */
    function hashing($string, $clean = false)
    {
        return Hash::create($string, $clean);
    }
}

if(!function_exists("pagination"))
{
    /**
     * Returns current pagination propertis 
     * 
     */

    function pagination() 
    {
        if(env("_pagination") != "") 
        {
            $properties = env("_pagination");
            $response = json_decode($properties);
            return $response;
        }

        return null;
    }
}