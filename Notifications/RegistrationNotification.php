<?php 

namespace App\Messages\Notification;

use App\Messages\Mail;

class RegistrationNotfication extends Mail {

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build() {
        
        $this->subject = "Account Confirmation";
        $this->message = "You just signup on this platform";
        $this->receiver = "akinniyiakinpelumi@gmail.com";
        $this->receiver_name = $this->user->name;

        $this->send();

    }


}