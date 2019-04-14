<?php 

namespace App\Core;



class Server {

    public function __construct($debug=true){
        
    }

    private function autoload(){
        include "./Config.php";

        # Embeding all app modules globally declearing the server::class
        include $Cores["dependecies"]["template"];
        include $Cores["router"]["routes"];
        include $Cores["router"]["request"];
        include $Cores["router"]["response"];
        include $Cores["database"]["path"];
        include $Cores["database"]["migrations"];
        include $Cores["middlewares"]["session"];
        # include $Cores["middlewares"]["cookies"];
        include $Cores["dependecies"]["controller"];
        include $Cores["helpers"]["fs"];

    }

    static public function start() {
        Server::autoload();
    }
}