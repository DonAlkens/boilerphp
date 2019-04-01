<?php 

class Session {

    static public function exists($name) {
        if(isset($_SESSION[$name])){
            return true;
        }
        return false;
    }

    static function start($name, $value) {
        $_SESSION[$name] = $value;
    }

    static function end($name) {
        unset($_SESSION[$name]);
    }

    static function get($name){
        return $_SESSION[$name];
    }
}



function HasPermission($sessionKey, $redirectUrl=null){
    if(isset($_SESSION[$sessionKey])){
        $logger = true;
    } 
    else {
        return Redirect($redirectUrl);
    }
}

