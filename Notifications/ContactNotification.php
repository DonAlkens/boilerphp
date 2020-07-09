<?php 

namespace App\Messages\Notification;

use App\Messages\Mail;


class ContactNotfication extends Mail {

    public function __construct($data, $email)
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