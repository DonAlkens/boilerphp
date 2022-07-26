<?php

namespace Tests;

use App\Core\Console\Console;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
	
	use Application;

	public function __construct()
  {
    parent::__construct();

    $this->startApplication();
  }
}
