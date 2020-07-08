<?php
session_start();

require __DIR__."/Core/autoload_modules.php";
require __DIR__."/Core/server.php";


/*
* -----------------------------------------------------
* Server Class for App Server
* -----------------------------------------------------
*/ 

use App\Core\Server;
$server = new Server($debug = true);


/*
* -----------------------------------------------------
* Server will be initialized and modules will be loaded
* using the load modules function from the server
* -----------------------------------------------------
*/ 

$server->load_modules($modules);


/*
* --------------------------------------------------------
* Routing will be handled by route class using the listen
* funtion from the Route Class
* -------------------------------------------------------
*/ 

$server->start();

