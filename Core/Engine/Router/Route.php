<?php

namespace App\Core\Urls;


use Exception;
use App\Config\RoutesConfig;
use App\Core\Database\Schema;
use App\Core\Engine\Exceptions\UnAuthorizedRequestException;
use App\Hashing\Hash;
use Auth;

class Route extends RoutesConfig
{

    static private $routes = array(
        "get"   =>  array(),
        "post"  =>  array()
    );



    static private $domains = array();




    static public $subdomain;




    static public $active_domain;




    static private $route_lookup_list;




    static private $controller_namespace = 'App\Action\Urls\Controllers\\';




    static private $group_path = "";



    static private $uri_;
    
    

    static private $middleware = "";



    public $names_ = [];



    public function __construct()
    {
    }


    static public function configure()
    {
        static::$domains[static::$domain] = array("get"   =>  array(), "post"  =>  array());
    }

    static public function subdomain($domains, $callback)
    {
        if (!static::$enable_subdomains) {
            // throw unenabled subdomain actions exceptions
            exit;
        }

        if (!is_array($domains)) {
            $domains = [$domains];
        }

        foreach ($domains as $domain) {
            static::$active_domain = $domain;
            static::$subdomain = static::$active_domain . "." . static::$domain;
            static::$domains[static::$subdomain] =  array("get"   =>  array(), "post"  =>  array());
            $callback();
            static::$subdomain = null;
        }
    }

    public function as($name)
    {
        $this->set_route_name($name);
    }


    static public function protected($middleware, $callback) 
    {
        static::$middleware = $middleware;
        $callback();
        static::$middleware = null;
    }

    static public function group($name, $callback)
    {
        static::$group_path = "/" . trim($name, "/");
        $callback();
        static::$group_path = "";
    }


    static public function httpAction($path, $controller)
    {
        static::$uri_ = $path;

        static::post($path, $controller);
        return static::get($path, $controller);
    }

    static public function get($path, $controller)
    {
        $map = static::create_map($path, "get", $controller);
        static::mapRoute($map);

        return static::route_modifier($map);
    }

    static public function post($path, $controller)
    {
        $map = static::create_map($path, "post", $controller);
        static::mapRoute($map);
    }


    static protected function create_map($path, $method, $controller)
    {

        $path = "/" . trim($path, "/");

        # check group path
        if (static::$group_path != "") {
            $path = static::$group_path . $path;
        }

        return array("url" => $path, "method" => $method, "action" => $controller);
    }

    static protected function mapRoute(array $route)
    {
        $method = strtolower($route["method"]);

        # get url
        $url = "/index" . $route["url"];

        # prepare properties
        $properties = array("action" => $route["action"]);

        $key = Route::interogate($url);

        # if interogate returned an array
        if (is_array($key)) {
            list($key, $params) = $key;

            # add the param key to the properties
            # validate param by
            # @cheking duplicate key
            $properties["param"] = $params;
        }

        # if middlewere is set
        if(static::$middleware != null) {
            if(strpos(static::$middleware, '|')) {
                $middlewares = explode('|', static::$middleware);
            } 
            else
            {
                $middlewares = [static::$middleware];
            } 
            
            $properties["protection"] = $middlewares;
        }

        # checking if url has already been registered
        if (static::$enable_subdomains) {
            if (static::$subdomain != null) {
                if (!array_key_exists($key, static::$domains[static::$subdomain][$method])) {
                    # register as new url path
                    static::$domains[static::$subdomain][$method][$key] = $properties;
                    return;
                }
            } else {
                if (!array_key_exists($key, static::$domains[static::$domain][$method])) {
                    # register as new url path
                    static::$domains[static::$domain][$method][$key] = $properties;
                    return;
                }
            }
        } else {
            if (!array_key_exists($key, static::$routes[$method])) {
                # register as new url path
                static::$routes[$method][$key] = $properties;
                return;
            }
        }


        # other wise throw double map error;
        # code...


    }

    static protected function patternHandler($lookup, $pattern, $uri, $method)
    {
        $path = $lookup[$pattern];

        # attaching the parameter values

        $splitPattern = explode("/", $pattern);
        $splitUri = explode("/", $uri);

        # get the intersect 
        $intersect = array_intersect($splitPattern, $splitUri);

        # get the diff between intersect and uri
        $params = array_diff($splitUri, $intersect);
        $p = [];

        foreach ($params as $key => $value) {
            array_push($p, $value);
        }

        #setting the parameter value
        $i = 0;
        foreach ($lookup[$pattern]["param"] as $key => $value) {
            $lookup[$pattern]["param"][$key] = $p[$i];
            $i++;
        }

        $request = new Request($method);
        $request->param = $lookup[$pattern]["param"];

        static::listenHandler($lookup, $pattern, $request);
    }


    protected static function authorize($path, Request $request) {
            
        $failed = false;

        $protections = $path['protection'];
        $headers = $request->headers();

        $responseFormat = $headers["Accept"];

        foreach($protections as $protection) {

            $type = null;

            if(strpos($protection, ':')) 
            {
                $xplode = explode(':', $protection);
                $protection = $xplode[0];
                $type = $xplode[1];
            }

            if(!array_key_exists($protection, $headers)) {
                $failed = true;

                if($responseFormat == 'application/json') {

                    $response = [
                        'success' => false,
                        'message' => 'Unauthorized Request'
                    ];
                }

                break;
            }

            if($protection == 'Authorization') {
                
                if($type == 'Bearer') {

                    $authToken = trim(preg_replace("/Bearer/", '', $headers['Authorization']));
                    $authToken = (new Hash)->getDecodedBase($authToken);
                    $authUser = (new Schema)->table('auth_access_tokens')->find('token', $authToken);

                    if($authUser) 
                    {
                        
                        $user = (new $authUser->token_type)->find($authUser->token_id);

                        if($user) {
                            Auth::login($user);
                        }
                        else
                        {
                            $failed = true;
                        }

                        (new Schema)->table('auth_access_tokens')->where('id', $authUser->id)->update([
                            'last_used_date' => $request->timestamp()
                        ]);
                    
                    }
                    else 
                    {
                        $failed = true;
                    }
                }
            }
        }

        if($failed) {

            if($responseFormat == 'application/json') {

                if(!isset($response)) {

                    $response = [
                        'success' => false,
                        'message' => 'Authorization failed: Incorrect credentials'
                    ];
                }

                Json($response, 400);
            } 
            else 
            {
                // throw new UnAuthorizedRequestException("Unauthorized request detected!");
                content('Authorization failed: Incorrect credentials');
            }

            return false;
        }
        
        return true;
    }

    protected static function listenHandler($lookup, $uri, $request)
    {

        $path = $lookup[$uri];

        if(isset($path['protection'])) {

            if(!Route::authorize($path, $request)) {
                exit;
            }
        }

        if (gettype($path["action"]) == "string") {
            $split_action = explode("::", $path["action"]);

            $controller = static::$controller_namespace . $split_action[0];
            $action = static::$controller_namespace . $path["action"];
            $handler_method = $split_action[1];

            //Call Coutroller to Load All MiddleWare and Auth
            $handler_controller = new $controller;
            $handler_controller->$handler_method($request);
        } else {
            $action = $path["action"];
            call_user_func($action, $request);
        }
    }

    static public function listen()
    {
        $uri = trim($_SERVER["REQUEST_URI"], "/");
        $method = strtolower($_SERVER["REQUEST_METHOD"]);
        $domain = $_SERVER['HTTP_HOST'];

        static::$route_lookup_list = static::$routes[$method];

        if (static::$enable_subdomains) {
            $domain = str_replace("www.", "", $domain);

            // Do some domain name checks here
            if (array_key_exists($domain, static::$domains)) {
                static::$route_lookup_list = static::$domains[$domain][$method];
            } else {
                if (array_key_exists("*." . static::$domain, static::$domains)) {
                    $wild_card_enabled = true;
                }
            }
        }

        if (preg_match('/\?/i', $uri)) {
            $uri = preg_replace("/\?(.*)/", "", $uri);
        }

        # if uri is empty after trim
        if (empty($uri)) {
            $uri = "index";
        } else {
            $uri = "index/" . $uri;
        }


        /**
         * if uri is registered in domain lookup method list
         */
        if (array_key_exists($uri, static::$route_lookup_list) && !isset($wild_card_enabled)) {
            static::listenHandler(static::$route_lookup_list, $uri, new Request($method));
        }
        /**
         * Checking wildcard domains if they are allowed
         */
        else if (static::$enable_subdomains && isset($wild_card_enabled)) {
            $wildcard = "*." . static::$domain;
            if (array_key_exists($wildcard, static::$domains)) {

                $request = new Request($method);
                $request->_domain = $domain;
                $request->_subdomain = explode(".", $domain)[0];

                static::$route_lookup_list = static::$domains[$wildcard][$method];
                if (array_key_exists($uri, static::$route_lookup_list)) {
                    static::listenHandler(static::$route_lookup_list, $uri, $request);
                } else {
                }
            }
        } else {
            # verify if the url pattern is registered
            # for url that have parameters
            $pattern = Route::verifyPattern($uri, $method);

            # checking it pattern exists
            if (array_key_exists($pattern, static::$route_lookup_list)) {
                static::patternHandler(static::$route_lookup_list, $pattern, $uri, $method);
                return;
            }

            if ($method === "post") {
                echo unhandledPost();
                return;
            }

            return error404();
        }
    }

    static public function pattern()
    {

        $registerer = static::$routes;

        if (static::$enable_subdomains) {
            $registerer = static::$domains;
        }

        echo json_encode($registerer);
    }

    static private function interogate($url)
    {
        # cleaning the url
        $clean = trim($url, "/");

        # if empty url [key will be 'index']
        if (empty($clean)) {
            return "index";
        }

        # if not trailing slash anymore but has param identifier [:]
        else if (!preg_match("/\//", $clean) && strpos($clean, ":")) {
            $clean = "index/" . $clean;
            $pp = Route::createPP($clean);
            return $pp;
        }

        # if param identifier [:]  exists in url
        else if (preg_match("/\//", $clean) && strpos($clean, ":")) {
            $pp = Route::createPP($clean);
            return $pp;
        }

        return $clean;
    }

    static private function createPP($clean)
    {
        $split = explode("/", $clean);
        $base = $split[0];
        $params = [];

        for ($i = 1; $i < count($split); $i++) {
            $path = $split[$i];
            if (strpos($path, "{") > -1 && strpos($path, ":") > -1 && strpos($path, "}") > -1) {
                $param = str_replace("{", "", str_replace("}", "", $path));

                $pS = explode(":", $param);
                $base .= "/~" . $pS[1];

                $key = $pS[0];
                if (array_key_exists($key, $params)) {
                    throw new Exception("Duplicate entry key for url parameter[" . $key . "]", 0);
                    exit;
                }
                $params[$key] = null;
            } else {
                $base .= "/" . $path;
            }
        }

        return [$base, $params];
    }

    static public function verifyPattern($uri, $method)
    {
        $split = explode("/", $uri);
        $base = '';

        $sub = '';
        $params = [];

        $j = 1;
        $numberRegisterUrl = count(static::$route_lookup_list);
        $pattern = '';

        while (true) {
            
            if ($j == count($split)) {
                break;
            }

            # get all base path 
            for ($b = 0; $b < $j; $b++) {
                $path = $split[$b];
                $base .= $path . "/";
            }

            #get all parameters as sub path
            for ($i = $j; $i < count($split); $i++) {
                $param = $split[$i];
                $type = (is_numeric($param)) ? "int" : "string";
                $sub .= "/~" . $type;
            }


            # merge base and sub path together
            $pattern = trim($base, "/") . $sub;

            # check if pattern exists
            if (array_key_exists($pattern, static::$route_lookup_list)) {
                break;
            }

            # if path does not exists and out of count
            // if ($j == ($numberRegisterUrl)) {
            //     break;
            // }

            # all variables to be back to initial state 
            # when j is to increment
            $pattern = "";
            $base = "";
            $sub = "";

            $j++;
        }

        # checking on index thats has parameters 
        // if(empty($pattern)) 
        // {
        //     $uri = "index/".$uri;
        //     $pattern = Route::verifyPattern($uri, $method);
        // }

        return $pattern;
    }


    /**
     * Bundle all route files create by devs
     * 
     *  @param $routes - Specify routes to be loaded
     *  Default null, and bundles all custom route files by default.
     *  Array value required to specify routes to be loaded
     * 
     * */

    public static function loadRoutes($routes = null)
    {

        if (!is_null($routes)) {

            foreach ($routes as $route) {
                $route_path = "./routes/" . $route . ".php";
                if (file_exists($route_path)) {
                    require_once $route_path;
                }
            }
        } else {
            foreach (glob("routes/*.php") as $route) {
                if (!preg_match('/route.php/', $route)) {
                    require $route;
                }
            }
        }
    }


    static public function route_modifier($map)
    {

        $route = (new Route);
        $route->map = $map;

        return $route;
    }

    public function set_route_name($name)
    {

        if (isset($_ENV['app_route_name_specifier'])) {
            $names = $_ENV["app_route_name_specifier"];
            if (array_key_exists($name, $names)) {
            } else {
                $_ENV['app_route_name_specifier'][$name]  = $this->map;
            }
        } else {
            $_ENV['app_route_name_specifier'] = [$name => $this->map];
        }
    }
}
