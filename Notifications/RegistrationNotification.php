<?php 

namespace App\Notification;

use App\User;
use App\Hashing\Hash;
use App\Messages\Mail\Mail;
use App\Messages\Notification;

class RegistrationNotification extends Notification {

    public function __construct(User $user, $params)
    {
        $this->user = $user;
        $this->params = $params;
        $this->sha = Hash::create($user->email, true);
    }
    
    public function build() {

        $data["user"] = $this->user;
        $data["sha"] = $this->sha;
        $data["params"] = $this->params;
        
        return (new Mail)->from('account@wearslot.com')
                    ->to($this->user->email, ($this->user->firstname." ".$this->user->lastname))
                    ->subject("Account Confirmation")
                    ->template($data, "mail/user/registration");
    }

}