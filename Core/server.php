<?php 

namespace App\Core;

use App\Core\Engine\Route;

class Server  {

    public function __construct($debug=true){
        $this->setEnv();
    }

    public function load_modules($modules){

        foreach($modules as $module) {

            foreach($module as $class) {
                $path_array = explode("::", $class);
                $full_file_path = join("/", $path_array);
                require  __DIR__."/".$full_file_path.".php";
            }
            
        }
        
        // require __DIR__."/../notifications/".$notification.".php";
    }

    public function setEnv()
    {
        $get_env_file = fopen(".env", "r");
        if($get_env_file){
            while(!feof($get_env_file)) {
                $line = fgets($get_env_file);
                $key_value = explode("=", $line);

                $key = trim($key_value[0], " ");
                $_ENV[$key] = trim($key_value[1], " "); 
            }
        }
        
    }

    public function init_route_handler() {
        require __DIR__."/../route.php";
        Route::listen();
    }

    public function load_app_models() {

        foreach(glob("Models/*.php") as $model) {
            require __DIR__."/../".$model;
        }
    }

    public function load_app_controllers() {
        foreach(glob("Controllers/*.php") as $controller) {
            require __DIR__."/../".$controller;
        }
    }

    public function start() {

        /*
        * Load all app models
        */ 
        $this->load_app_models();
        
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