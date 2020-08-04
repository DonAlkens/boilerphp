<?php

require __DIR__."/Core/app_loader.php";

/*
* -----------------------------------------------------
* Include server class namespace
* -----------------------------------------------------
*/ 
use App\Core\Server;

/*
* -----------------------------------------------------
* Initialize App Server
* -----------------------------------------------------
*/ 

$server = new Server(new App\Core\Modules\AppModules, $debug = true);

/*
* -----------------------------------------------------
* Server will be initialized and modules will be loaded
* using the load modules function from the server
* -----------------------------------------------------
*/ 

$server->load_app_modules();

/*
* --------------------------------------------------------
* Routing will be handled by route class using the listen
* function from the Route Class
* -------------------------------------------------------
*/ 

$server->start();