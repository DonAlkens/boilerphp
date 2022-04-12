<?php

namespace App\Messages\Notification;

use App\Messages\Mail\Mail;

class Notify extends Mail implements NotifyBuilderInterface
{

    public function __construct()
    {
    }

    public function build()
    {
        return $this->build();
    }
}
