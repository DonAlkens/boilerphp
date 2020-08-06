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
* Server will load configurations
* -----------------------------------------------------
*/ 

$server->load_configurations();

/*
* -----------------------------------------------------
* Server will be initialized and modules will be loaded
* -----------------------------------------------------
*/ 

$server->load_app_modules();


/*
* --------------------------------------------------------
* Server will be start listening to url actions
* -------------------------------------------------------
*/ 

$server->start();