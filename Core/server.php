<?php

namespace App\Core;

use App\Core\Modules\AppModules;
use Session;
use App\Core\Urls\Route;

class Server
{

    public function __construct(AppModules $app_modules, $debug = true)
    {

        $this->setEnv();

        $this->load_headers();

        $this->ModuleClass = $app_modules;

        $this->load_configurations();

        $this->load_app_modules();
    }

    public function load_headers()
    {
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
    }

    public function load_app_modules()
    {
        foreach ($this->ModuleClass->modules as $module) {

            foreach ($module as $class) {
                $path_array = explode("::", $class);
                $full_file_path = join("/", $path_array);
                require  __DIR__ . "/" . $full_file_path . ".php";
            }
        }

        # User Modules
        $this->load_user_modules();
    }

    public function load_user_modules()
    {
        if(count($this->ModuleClass->user_modules) > 0){
            foreach ($this->ModuleClass->user_modules as $module) 
            {
                if($module != "") {
                    $path_array = explode("::", $module);
                    $full_file_path = join("/", $path_array);
                    require $full_file_path . ".php";
                }
            }
        }
    }

    public function load_configurations()
    {

        foreach ($this->ModuleClass->configs as $configurations) {
            $path_array = explode("::", $configurations);
            $full_file_path = join("/", $path_array);
            require  __DIR__ . "/../" . $full_file_path . ".php";
        }
    }

    public function load_router_modules()
    {
        foreach ($this->ModuleClass->router_modules as $module) {
            $path_array = explode("::", $module);
            $full_file_path = join("/", $path_array);
            require  __DIR__ . "/" . $full_file_path . ".php";
        }
    }

    public function load_socket_modules() {

        foreach ($this->ModuleClass->socket_modules as $module) {
            $path_array = explode("::", $module);
            $full_file_path = join("/", $path_array);
            require  __DIR__ . "/" . $full_file_path . ".php";
        }
    }

    public function setEnv()
    {
        $get_env_file = fopen(".env", "r");
        if ($get_env_file) {
            while (!feof($get_env_file)) {
                $line = fgets($get_env_file);
                $key_value = explode("=", $line);

                if (isset($key_value[0]) && isset($key_value[1])) {
                    $key = trim($key_value[0], " ");
                    $_ENV[$key] = trim($key_value[1], " ");
                }
            }
        }
    }

    public function init_route_handler()
    {
        require __DIR__ . "/../routes/route.php";

        // Route::pattern();
        Route::listen();
    }

    public function load_app_models()
    {
        foreach (glob("app/Models/*.php") as $model) {
            require __DIR__ . "/../" . $model;
        }
    }

    public function load_app_notifications()
    {
        foreach (glob("app/Notifications/*.php") as $notification) {
            require __DIR__ . "/../" . $notification;
        }
    }

    public function load_app_controllers($_path)
    {
        // Scan controller director
        $controllers = scandir($_path);

        foreach ($controllers as $controller) {

            if (($controller != '.') && ($controller != '..')) {

                if (is_dir($_path . '/' . $controller)) {

                    $this->load_app_controllers($_path . '/' . $controller);
                } else {

                    $check_extension = explode(".", $_path . '/' . $controller);

                    if (end($check_extension) == "php") {

                        $path_to_controller = $_path . '/' . $controller;
                        require __DIR__ . "/../" . $path_to_controller;
                    }
                }
            }
        }
    }

    public function start()
    {

        (new Session)->initialize();

        /*
        * Load all app models
        */
        $this->load_app_models();

        /*
        * Load all app notifications
        */
        $this->load_app_notifications();

        /*
        * Load all app controllers
        */
        $this->load_app_controllers("app/Controllers");

        /*
        * Load router modules
        */
        $this->load_router_modules();

        /*
        * Checks if subdomains is enable and configures 
        * app for subdomain urls
        *
        */
        if (Route::$enable_subdomains) {
            Route::configure();
        }

        /*
        * Initialize route handler
        */
        $this->init_route_handler();
    }
}
