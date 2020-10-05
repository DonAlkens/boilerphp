<?php

namespace App\Core\Urls;


use Error, Exception;
use App\Config\RoutesConfig;


class Route extends RoutesConfig {

    static private $routes = array(
        "get"   =>  array(),
        "post"  =>  array()
    );

    static private $domains = array();

    
    static private $subdomain;


    static private $route_lookup_list;


    static private $controller_namespace = 'App\Action\Urls\Controllers\\';


    public function __construct()
    {

    }

    static public function configure()
    {
        static::$domains[static::$domain] = array( "get"   =>  array(), "post"  =>  array());
    }

    static public function subdomain($domain, $callback)
    {
        if(!static::$enable_subdomains)
        {
            // throw unenabled subdomain actions exceptions
            exit;
        }

        static::$subdomain = $domain.".".static::$domain;
        static::$domains[static::$subdomain] =  array( "get"   =>  array(), "post"  =>  array());
        $callback();
    }

    static public function httpAction($path, $controller)
    {
        static::get($path, $controller);
        static::post($path, $controller);
    }

    static public function get($path, $controller)
    {
        $map = array( "url" => $path, "method" => "get", "action" => $controller);
        static::mapRoute($map);
    }

    static public function post($path, $controller)
    {
        $map = array( "url" => $path, "method" => "post", "action" => $controller);
        static::mapRoute($map);
    }

    static public function mapRoute(Array $route) 
    {
        $method = strtolower($route["method"]);

        # get url
        $url = "/index".$route["url"];

        # prepare properties
        $properties = array("action" => $route["action"]);

        $key = Route::interogate($url);

        # if interogate returned an array
        if(is_array($key))
        {
            list($key, $params) = $key;
            
            # add the param key to the properties
            # validate param by
            # @cheking duplicate key
            $properties["param"] = $params;
        }

        # checking if url has already been registered
        if(static::$enable_subdomains) 
        {
            if(!array_key_exists($key, static::$domains[static::$subdomain][$method])) 
            {
                # register as new url path
                static::$domains[static::$subdomain][$method][$key] = $properties;
                return;
            } 
        }
        else 
        {
            if(!array_key_exists($key, static::$routes[$method])) 
            {
                # register as new url path
                static::$routes[$method][$key] = $properties;
                return;
            }
        }


        # other wise throw double map error;
        # code...


    }

    static public function listen()
    {
        $uri = trim($_SERVER["REQUEST_URI"],"/");
        $method = strtolower($_SERVER["REQUEST_METHOD"]);

        static::$route_lookup_list = static::$routes[$method];

        if(static::$enable_subdomains) 
        {
            $domain = $_SERVER['HTTP_HOST'];

            // Do some domain name checks here

            static::$route_lookup_list = static::$domains[$domain][$method];
        }

        if(preg_match('/\?/i', $uri))
        {
            $uri = preg_replace("/\?(.*)/", "", $uri);
        }
        
        # if uri is empty after trim
        if(empty($uri)) 
        {
            $uri = "index";
        } 
        else 
        {
            $uri = "index/".$uri;
        }


        # if uri is registered in method class
        if(array_key_exists($uri, static::$route_lookup_list))
        {

            $path = static::$route_lookup_list[$uri];

            $split_action = explode("::", $path["action"]);

            $controller = static::$controller_namespace.$split_action[0];
            $action = static::$controller_namespace.$path["action"];

            //Call Coutroller to Load All MiddleWare and Auth
            new $controller;

            call_user_func($action, new Request($method) );
            
        } 
        else 
        {
            # verify if the url pattern is registered
            # for url that have parameters
            $pattern = Route::verifyPattern($uri, $method);
            
            # checking it pattern exists
            if(array_key_exists($pattern, static::$route_lookup_list))
            {
                $path = static::$route_lookup_list[$pattern];

                # attaching the parameter values

                $splitPattern = explode("/",$pattern);
                $splitUri = explode("/",$uri);

                # get the intersect 
                $intersect = array_intersect($splitPattern,$splitUri);

                # get the diff between intersect and uri
                $params = array_diff($splitUri, $intersect);
                $p = [];
                
                foreach ($params as $key => $value) 
                {
                    array_push($p, $value);
                }

                #setting the parameter value
                $i = 0;
                foreach (static::$route_lookup_list[$pattern]["param"] as $key => $value) 
                {
                    static::$route_lookup_list[$pattern]["param"][$key] = $p[$i];
                    $i++;
                }

                $request = new Request($method);
                $request->param = static::$route_lookup_list[$pattern]["param"];


                $split_action = explode("::", $path["action"]);

                $controller = static::$controller_namespace.$split_action[0];
                $action = static::$controller_namespace.$path["action"];


                //Call Coutroller to Load All MiddleWare and Auth
                new $controller();

                
                call_user_func($action, $request);

                return;
            }

            if($method === "post")
            {
                echo unhandledPost();
                return;
            }

            return error404();
        }

    }

    static public function pattern()
    {

        $registerer = static::$routes;

        if(static::$enable_subdomains)
        {
            $registerer = static::$domains;
        }

        echo json_encode($registerer);
    }

    static private function interogate($url){
        # cleaning the url
        $clean = trim($url,"/");

        # if empty url [key will be 'index']
        if(empty($clean)){
            return "index";
        }

        # if not trailing slash anymore but has param identifier [:]
        else if(!preg_match("/\//",$clean) && strpos($clean,":"))
        {
            $clean = "index/".$clean;
            $pp = Route::createPP($clean);
            return $pp;
        }

        # if param identifier [:]  exists in url
        else if(preg_match("/\//",$clean) && strpos($clean,":")) 
        {
            $pp = Route::createPP($clean);
            return $pp;
        } 

        return $clean;
    }

    static private function createPP($clean)
    {
        $split = explode("/",$clean);
        $base = $split[0];
        $params = [];

        for($i = 1; $i < count($split); $i++)
        {
            $path = $split[$i];
            if(strpos($path,"{") > -1 && strpos($path,":") > -1 && strpos($path,"}") > -1)  
            {
                $param = str_replace("{","",str_replace("}","",$path));

                $pS = explode(":",$param);
                $base .= "/~".$pS[1];

                $key = $pS[0];
                if(array_key_exists($key, $params))
                {
                    throw new Exception("Duplicate entry key for url parameter[".$key."]", 0);
                    exit;
                }
                $params[$key] = null;
            } 
            
            else 
            {
                $base .= "/".$path;
            }
        }

        return [$base, $params];
    }

    static public function verifyPattern($uri, $method)
    {
        $split = explode("/",$uri);
        $base = '';

        $sub = '';
        $params = [];

        $j = 1;
        $numberRegisterUrl = count(static::$route_lookup_list);
        $pattern = '';

        while(true) 
        {

            if($j == count($split)){break;}
            
            # get all base path 
            for($b = 0; $b < $j; $b++) 
            {
                $path = $split[$b];
                $base .= $path."/";
            }

            #get all parameters as sub path
            for($i = $j; $i < count($split); $i++) 
            {
                $param = $split[$i];
                $type = (is_numeric($param)) ? "int" : "string";
                $sub .= "/~".$type;
            }

          
            # merge base and sub path together
            $pattern = trim($base,"/").$sub;

            # check if pattern exists
            if(array_key_exists($pattern, static::$route_lookup_list)) 
            {
                break;
            }

            # if path does not exists and out of count
            if($j == ($numberRegisterUrl)) 
            {
                break;
            }

            # all variables to be back to initial state 
            # when j is to increment
            $pattern = "";
            $base = "";
            $sub = "";

            $j++;
        }

        # checking on index thats has parameters 
        if(empty($pattern)) 
        {
            $uri = "index/".$uri;
            $pattern = Route::verifyPattern($uri, $method);
        }

        return $pattern;
    }
}