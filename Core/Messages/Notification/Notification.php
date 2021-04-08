<?php

namespace App\Messages;

use App\Messages\Notification\Notify;
use App\Messages\Mail\Mail;

class Notification extends Notify
{

    public function __construct()
    {
    }

    public function send() 
    {
        if($this->build() instanceof Mail) 
        {
            return $this->build()->send();
        }
        else 
        {
            return $this->build();
        }
    }

}
