<?php

namespace App\Core\Engine\Socket;

use App\Core\Server;

class SocketManager {

    public function __construct(Server $server, $argv) {

        
        $server->load_socket_modules();

        $server->load_app_models();
        $server->load_app_notifications();
        

        $this->arguments = $argv;

    }

    public function run() {

        if($this->validateCommand()) {
            if($this->getSocketName()) {
                $this->loadSocket();
                $this->startSocketServer();
            }
        }

        exit;
    }

    public function startSocketServer() {

        $namespace = "App\WebSocket\\";

        $socket = "{$namespace}{$this->socket}";
        $server = new $socket();
        echo "WebSocket Server Started on {$server->host}:{$server->port}\n";
        $server->run();
    }


    public function validateCommand() {

        if($this->argsLength() > 2 || $this->argsLength() < 2) {
            echo "Invalid commands.\n";
            return false;
        }

        return true;
    }


    public function argsLength() {
        return count($this->arguments);
    }

    public function getSocketName() {

        $this->socket = end($this->arguments);
        if($this->path($this->socket)) {
            return $this->socket;
        }

        return null;
    }

    public function path($file) {
        
        $filepath = "Sockets/{$file}.php";
        if(file_exists($filepath)) {
            $this->path = $filepath;
            return true;
        }
        
        return false;
    }

    public function loadSocket() {

        require "{$this->path}";
        echo "Loading {$this->socket} socket...\n";
    }

}