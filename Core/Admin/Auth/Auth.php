<?php

namespace App\Admin;

class Auth {
    
    static public function user(){
        if(isset($_SESSION["auth"])) {
            return unserialize($_SESSION["auth"]);
        }

        return false;
    }

    static public function logout(){
        unset($_SESSION["auth"]);
    }

    static public function login($user) {
        $_SESSION["auth"] = serialize($user);
    }

}
