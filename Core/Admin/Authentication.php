<?php

namespace App\Admin;

use App\Core\Database\Schema;
use App\User;

class Auth
{
    
    static public function user()
    {

        if(isset($_SESSION["auth"])) 
        {
            $id = $_SESSION["auth"];
            return (new User)->where("id", $id)->get();
        }

        return null;
    }

    static public function logout()
    {
        unset($_SESSION["auth"]);
    }

    static public function login($user) 
    {
        $_SESSION["auth"] = $user->id;
    }

}
