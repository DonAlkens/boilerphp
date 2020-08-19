<?php 

namespace App\Config;

class MailConfig 
{

    /**
    * SMTP Host
    * @var string
    *
    */
    static public $smtp = true;


    /**
    * SMTP Host
    * @var string
    *
    */
    static public $host = "";


    /**
    * SMTP Username
    * @var string
    *
    */
    static public $smtp_username = "";


    /**
    * SMTP Password
    * @var string
    *
    */
    static public $smtp_password = "";


    /**
    * SMTP Port no.
    * @var int
    *
    */
    static public $port = 487;


    /**
    * TLS encryption
    * @var bool
    *
    */
    static public $tls = true;

}