<?php

/*
* -----------------------------------------------------
* Include App class
* -----------------------------------------------------
*/ 
require __DIR__."/../configs/App.php";


/*
* ------------------------------------
* Load all third party modules
* install buy composer package manager
* ------------------------------------
*/ 
require __DIR__."/../vendor/autoload.php";


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

$server = new Server($debug = true);


/*
* --------------------------------------------------------
* Server will be start listening to url actions
* -------------------------------------------------------
*/ 

$server->start();