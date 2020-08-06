<?php 

namespace App\Core;

use App\Core\Urls\Route;

class Server  {

    public function __construct($app_modules, $debug=true)
    {
        
        $this->setEnv();
        
        $this->app_configurations = $app_modules->configurations;
        $this->app_modules = $app_modules->modules;

    }

    public function load_app_modules()
    {

        foreach($this->app_modules as $module) 
        {

            foreach($module as $class) 
            {
                $path_array = explode("::", $class);
                $full_file_path = join("/", $path_array);
                require  __DIR__."/".$full_file_path.".php";
            }
            
        }
        
    }

    public function load_configurations()
    {

        foreach($this->app_configurations as $configurations) 
        {
            $path_array = explode("::", $configurations);
            $full_file_path = join("/", $path_array);
            require  __DIR__."/../".$full_file_path.".php";
        }
        
    }

    public function setEnv()
    {
        $get_env_file = fopen(".env", "r");
        if($get_env_file)
        {
            while(!feof($get_env_file)) 
            {
                $line = fgets($get_env_file);
                $key_value = explode("=", $line);

                $key = trim($key_value[0], " ");
                $_ENV[$key] = trim($key_value[1], " "); 
            }
        }
        
    }

    public function init_route_handler() 
    {
        require __DIR__."/../route.php";
        // Route::pattern();
        Route::listen();
    }

    public function load_app_models() 
    {
        foreach(glob("Models/*.php") as $model) 
        {
            require __DIR__."/../".$model;
        }
    }

    public function load_app_notifications() 
    {
        foreach(glob("Notifications/*.php") as $notification) 
        {
            require __DIR__."/../".$notification;
        }
    }

    public function load_app_controllers() 
    {
        foreach(glob("Controllers/*.php") as $controller) 
        {
            require __DIR__."/../".$controller;
        }
    }

    public function start() 
    {

        session_start();

        /*
        * checks if subdomains is enable and
        * cnfigures app for subdomain urls
        */ 
        if(Route::$enable_subdomains) 
        {
            Route::configure();
        }
        
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
        $this->load_app_controllers();

        /*
        * Initialize route handler
        */
        $this->init_route_handler();

    }
}