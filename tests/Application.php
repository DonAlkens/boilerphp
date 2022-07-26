<?php

namespace Tests;

use App\Core\Console\Console;
use App\Core\Server;

trait Application
{

  function startApplication()
  {

    $console = new Console(server: new Server(), verbose: false);

    $console->command('migrate --fresh');
		$console->command('db seed');

  }
}
