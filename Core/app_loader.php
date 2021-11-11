<?php 

/*
* ------------------------------------
* Load all third party modules
* install buy composer package manager
* ------------------------------------
*/ 
require __DIR__."/../vendor/autoload.php";


/*
* -----------------------------
* Load all in build app modules
* -----------------------------
*/ 
require __DIR__."/autoload_modules.php";


/*
* -----------------------------
* Load app server class for app
* kickstart and initialization
* -----------------------------
*/ 
require __DIR__."/server.php";