<?php

namespace App\Messages\Mail;

class Mail extends MailSender
{

    public function __construct()
    {
    }

    public function send()
    {
        return $this->sendMail();
    }

}
