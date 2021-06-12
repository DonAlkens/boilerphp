<?php

require __DIR__."/core/app_loader.php";

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
* --------------------------------------------------------
* Server will be start listening to url actions
* -------------------------------------------------------
*/ 

$server->start();