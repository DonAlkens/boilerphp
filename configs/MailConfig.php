<?php 

namespace App\Config;

class MailConfig 
{
    /**
    * Sender of the mail
    * @var string
    *
    */
    public $from = "info@boiler.com";


    /**
    * The name of the sender
    * @var string
    *
    */
    public $fromName = "boiler";


    /**
    * Receipient of the mail
    * @var string
    *
    */
    public $to;


    /**
    * The name of the 
    * Receipient of the mail
    * @var string
    *
    */
    public $toName;


    /**
    * Reply to 
    * @var string
    *
    */
    public $replyTo = "no-reply@example.com";


    /**
    * Reply name
    * @var string
    *
    */
    public $replyName = "No-reply";


    /**
    * SMTP Host
    * @var string
    *
    */
    public $smtp = true;


    /**
    * TLS encryption
    * @var bool
    *
    */
    public $tls = true;


}