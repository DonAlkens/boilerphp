<?php


namespace Console;

class Console extends Command
{

    public function __construct($server, $argv = null)
    {
        $this->server = $server;
        $this->arguments = $argv;
    }

    public function run()
    {
        array_splice($this->arguments, 0, 1);
        if ($this->getCommandLength($this->arguments) > 0) {
            $this->parse($this->arguments);
        }
    }

    public function parse($arguments)
    {
        $command = $arguments[0];

        if (in_array($command, $this->commands)) {
            // Remove command from arguments 
            array_splice($arguments, 0, 1);
            // Use function to execute commands
            $this->$command($arguments);
        }
    }
}
