<?php

namespace App\Admin;

use Session;
use App\User;

class Authentication
{
    
    static public function user()
    {

        if(Session::get("auth")) 
        {
            $id = Session::get("auth");
            return (new User)->find($id);
        }

        return null;
    }

    static public function logout()
    {
        Session::end("auth");
        Session::end("app_doors_locks");
        Session::end("request_validation_message");

    }

    static public function login($user) 
    {
        Session::set("auth", $user->id);
    }
}
