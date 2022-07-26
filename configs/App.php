<?php

namespace App\Config;

class App
{

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
    protected $cookie_subdomain = ".boiler.com";

    /**
     * App headers
     *
     * @var array
     *
     */
    protected $headers = [];

    /**
     * Enabled domains for cors check
     *
     * @var array
    */
    protected $allowed_domains = [];



    protected function ignition() 
    {
        
    }

}
