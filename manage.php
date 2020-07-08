<?php

require __DIR__.'/core/Console/Console.php';

use Console\Console;

/*
|--------------------------------------------
| Initialize and Run the Console Application
|--------------------------------------------
|
| ALl command run on the cli will be handle and 
| response will be sent back to the terminal
|
*/

$console = new Console($argv);
$console->run();
