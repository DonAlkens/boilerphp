<?php

namespace App\Core\Urls;

use Error, Exception;


class Route {

    static private $routes = array(
        "get"   =>  array(),
        "post"  =>  array()
    );

    static private $lastUrl = "";

    public function __construct(){

    }

    static public function HttpAction($path, $controller){
        $map = [
            "url" => $path,
            "method" => "get",
            "action" => $controller
        ];
        Route::mapRoute($map);

        $map = [
            "url" => $path,
            "method" => "post",
            "action" => $controller
        ];
        Route::mapRoute($map);
    }

    static public function get($path, $controller){
        $map = [
            "url" => $path,
            "method" => "get",
            "action" => $controller
        ];
        Route::mapRoute($map);
    }

    static public function post($path, $controller){
        $map = [
            "url" => $path,
            "method" => "post",
            "action" => $controller
        ];
        Route::mapRoute($map);
    }

    static public function mapRoute(Array $route) {
        $method = strtolower($route["method"]);
        $set = self::$routes[$method];

        # get url
        $url = "/index".$route["url"];
        # prepare properties
        $properties = array("action" => $route["action"]);

        $key = Route::interogate($url);

        # if interogate returned an array
        if(is_array($key)){
            list($key, $params) = $key;
            
            # add the param key to the properties
            # validate param by
            # @cheking duplicate key
            $properties["param"] = $params;
        }

        # checking if url has already been registered
        if(!array_key_exists($key, self::$routes[$method])) {
            # register as new url path
            self::$routes[$method][$key] = $properties;
            return;
        } 

        # other wise throw double map error;
        # code...


    }

    static public function listen(){
        $uri = trim($_SERVER["REQUEST_URI"],"/");
        $method = strtolower($_SERVER["REQUEST_METHOD"]);

        if(strpos($uri,"?")){
            $uri = preg_replace("/\?(.*)/","",$uri);
        }

        # if uri is empty after trim
        if(empty($uri)) {
            $uri = "index";
        } else {
            $uri = "index/".$uri;
        }


        # if uri is registered in method class
        if(array_key_exists($uri, self::$routes[$method])){

            $path = self::$routes[$method][$uri];

            $controller = explode("::", $path["action"])[0];
            //Call Coutroller to Load All MiddleWare and Auth
            new $controller();

            call_user_func($path["action"], new Request($method) );

            self::$lastUrl = $path;
            
        } 
        else {

            # verify if the url pattern is registered
            # for url that have parameters
            $pattern = Route::verifyPattern($uri, $method);
            
            # checking it pattern exists
            if(array_key_exists($pattern, self::$routes[$method])){
                $path = self::$routes[$method][$pattern];

                # attaching the parameter values

                $splitPattern = explode("/",$pattern);
                $splitUri = explode("/",$uri);

                # get the intersect 
                $intersect = array_intersect($splitPattern,$splitUri);
                # get the diff between intersect and uri
                $params = array_diff($splitUri, $intersect);
                $p = [];
                foreach ($params as $key => $value) {
                    array_push($p, $value);
                }

                #setting the parameter value
                $i = 0;
                foreach (self::$routes[$method][$pattern]["param"] as $key => $value) {
                    self::$routes[$method][$pattern]["param"][$key] = $p[$i];
                    $i++;
                }

                $request = new Request($method);
                $request->param = self::$routes[$method][$pattern]["param"];
                $request->map($request->result);

                $controller = explode("::", $path["action"])[0];
                //Call Coutroller to Load All MiddleWare and Auth
                new $controller();
                
                echo call_user_func($path["action"], $request);

                self::$lastUrl = $path;
                return;
            }

            if($method === "post"){
                echo unhandledPost();
                return;
            }

            return error404();
        }

    }

    static public function pattern(){
        echo json_encode(self::$routes);
    }

    static private function interogate($url){
        # cleaning the url
        $clean = trim($url,"/");

        # if empty url [key will be 'index']
        if(empty($clean)){
            return "index";
        }

        # if not trailing slash anymore but has param identifier [:]
        else if(!preg_match("/\//",$clean) && strpos($clean,":")){
            $clean = "index/".$clean;
            $pp = Route::createPP($clean);
            return $pp;
        }

        # if param identifier [:]  exists in url
        else if(preg_match("/\//",$clean) && strpos($clean,":")) {
            $pp = Route::createPP($clean);
            return $pp;
        } 

        return $clean;
    }

    static private function createPP($clean){
        $split = explode("/",$clean);
        $base = $split[0];
        $params = [];

        for($i = 1; $i < count($split); $i++){
            $path = $split[$i];
            if(strpos($path,"{") > -1 && strpos($path,":") > -1 && strpos($path,"}") > -1)  {
                $param = str_replace("{","",str_replace("}","",$path));

                $pS = explode(":",$param);
                $base .= "/~".$pS[1];

                $key = $pS[0];
                if(array_key_exists($key, $params)){
                    throw new Exception("Duplicate entry key for url parameter[".$key."]", 0);
                    exit;
                }
                $params[$key] = null;
            } 
            
            else {
                $base .= "/".$path;
            }
        }

        return [$base, $params];
    }

    static public function verifyPattern($uri, $method){
        $split = explode("/",$uri);
        $base = '';

        $sub = '';
        $params = [];

        $j = 1;
        $numberRegisterUrl = count(self::$routes[$method]);
        $pattern = '';

        while(true) {

            if($j == count($split)){break;}
            
            # get all base path 
            for($b = 0; $b < $j; $b++) {
                $path = $split[$b];
                $base .= $path."/";
            }

            #get all parameters as sub path
            for($i = $j; $i < count($split); $i++) {
                $param = $split[$i];
                $type = (is_numeric($param)) ? "int" : "string";
                $sub .= "/~".$type;
            }

          
            # merge base and sub path together
            $pattern = trim($base,"/").$sub;

            # check if pattern exists
            if(array_key_exists($pattern, self::$routes[$method])) {
                break;
            }

            # if path does not exists and out of count
            if($j == ($numberRegisterUrl)) {
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
        if(empty($pattern)) {
            $uri = "index/".$uri;
            $pattern = Route::verifyPattern($uri, $method);
        }

        return $pattern;
    }
}