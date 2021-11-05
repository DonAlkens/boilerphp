<?php 

namespace App\Notification;

use App\Messages\Mail\Mail;
use App\Messages\Notification;
use App\PasswordReset;
use App\User;

class PasswordResetNotification extends Notification {

    public function __construct(User $user, PasswordReset $reset, $params)
    {
        $this->user = $user;
        $this->reset = $reset;
        $this->params = $params;
    }
    
    public function build() 
    {
        $data["user"] = $this->user;
        $data["reset"] = $this->reset;
        $data["params"] = $this->params;
         
        return (new Mail)->from('account@wearslot.com')
                    ->to($this->user->email, ($this->user->firstname." ".$this->user->lastname))
                    ->subject("Password Reset Notification")
                    ->template($data, "mail/user/password-reset");
    }

}