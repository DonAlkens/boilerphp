<?php 

namespace App\Messages\Notification;

use App\Messages\Mail;

class ContactNotfication extends Mail {

    public function __construct()
    {
        // code ...
    }

    public function build() {
        
        $this->receiver = "contact@aprilvines.com";
        $this->receiver_name = "April Vines";
        $this->send();

    }


}