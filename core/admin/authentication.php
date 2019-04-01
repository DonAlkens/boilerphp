<?php

namespace App\Admin;

class Auth {

    static private $id = null;
    static private $auth = null;

    public function id($key=null){
        if(!is_null($key)){
            return self::$id = $_SESSION["user"][$key];
        }
        return self::$id = $_SESSION["user"]["id"];
    }

    public function get_auth_user(){
        self::$auth = $_SESSION["user"];
        return self::$auth;
    }

    public function logout(){
        unset($_SESSION["user"]);
    }

    public function login($user) {
        $_SESSION["user"] = $user;
    }
}
