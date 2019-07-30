<?php
session_start();
include "./core/admin/authentication.php";
# include "./Config.php";
include "./core/server.php";
use App\Core\Server;


# Starting the App Server 
Server::start();


# Registering a listening route
include "route.php";
Route::listen();
#Route::pattern();

include "migrations.php";

function __autoload($class_name){
        if(file_exists("./controllers/".$class_name.".php")){
                include "./controllers/".$class_name.".php";
        } 
        else if(file_exists("./models/".$class_name.".php")) {
                include "./models/".$class_name.".php";
        }
}

?>