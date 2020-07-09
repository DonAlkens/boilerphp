<?php


namespace Console;

require "Command.php";
use CLI\CLI_Helper;

class Console extends Command {

    public function __construct($argv = null)
    {
        $this->arguments = $argv;
    }

    public function run()
    {   
        array_splice($this->arguments, 0, 1);
        if($this->length($this->arguments) > 0) {
            $this->parse($this->arguments);
        } 

    }

    public function parse($arguments) {

        $command = $arguments[0];

        if(in_array($command, $this->commands)) {
            // Remove command from arguments 
            array_splice($arguments, 0, 1);
            
            // Use function to execute commands
            $this->$command($arguments);
        }


    }
}