<?php 

namespace App\Notification;

use App\Messages\Mail\Mail;
use App\Messages\Notification;


class TestNotification extends Notification {

    public function __construct()
    {
        
    }
    
    public function build() 
    {
        return (new Mail)->from("email@example.com", "John Doe")
                ->to("user@example.com", "User Name")
                ->subject("BoilerPHP Notification")
                ->message("Hello, This is a boilerphp notication");
    }

}