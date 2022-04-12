<?php 

/**
 * load Console HelpersInterface
 */
require_once __DIR__."/Console/src/ActionHelpersInterface.php";

/**
 * load Console Helpers Class
 */
require_once __DIR__."/Console/src/ActionHelpers.php";

/**
 * load Console Action Class
 */
require_once __DIR__."/Console/src/Actions.php";


/**
 * Load command class
 */
require __DIR__."/Console/Command.php";


/**
 * Load console app class
 */
require __DIR__."/Console/Console.php";


/**
* Load all in build app modules
*/ 
require __DIR__."/autoload_modules.php";


/**
* Load app server class for app
*/ 
require __DIR__."/server.php";