<?php 

namespace App\Config;

class App {

    /**
    * Set session lifetime 
    *
    * @var int
    *
    */
    public $session_lifetime = 172800;

    /**
    * Set session across subdomains
    *
    * @var string
    *
    */
    public $cookie_subdomain = "";

}