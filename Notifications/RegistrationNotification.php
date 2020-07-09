<?php 

namespace App\Messages\Notification;

use App\Messages\Mail;


class RegistrationNotfication extends Mail {

    public function __construct($email, $data)
    {
        $this->email = $email;
        $this->data = $data;
        
        parent::__construct();
    }

    public function build() 
    {
        
    }

    public function config()
    {
        
    }


}