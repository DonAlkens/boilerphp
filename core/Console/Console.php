<?php


namespace Console;

include "Command.php";
use CLI\CLI_Helper;

class Console extends Command {

    public $command;

    public function __construct($argv = null)
    {
        $this->command = $argv;
    }

    public function run()
    {   
        array_splice($this->command, 0, 1);
    }

}