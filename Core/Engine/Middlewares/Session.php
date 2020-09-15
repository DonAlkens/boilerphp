<?php 

use App\Config\App;

class Session extends App
{

    public function initialize() {

        ini_set('session.gc_maxlifetime', $this->session_lifetime);
        session_start();
    }

    public static function exists($name) {
        
        if(isset($_SESSION[$name])) {

            return true;
        }
        return false;
    }

    public static function set($name, $value) {
        
        $_SESSION[$name] = $value;
    }

    public static function end($name) {
        
        unset($_SESSION[$name]);
    }

    public static function get($name) {

        if(isset($_SESSION[$name])) {

            return $_SESSION[$name];
        } 

        return false;
    }

    public static function clear() {

        $_SESSION == null; 
        if(session_destroy()) { return true; }

    }
}

