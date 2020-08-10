<?php

namespace App\Admin;

use App\Core\Database\Schema;

class Auth
{
    
    static public function user()
    {

        if(isset($_SESSION["auth"])) 
        {
            $id = $_SESSION["auth"];
            return (new Schema)->setTable("users")->where("id", $id)->get();
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
