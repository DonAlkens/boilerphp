<?php

namespace App\Hashing;

class Hash {

    static public function create($string, $clean = false) 
    {
        
        if($clean == true)
        {
            $string = password_hash($string, PASSWORD_BCRYPT);
            $hash = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        }
        else
        {
            $hash = password_hash($string, PASSWORD_BCRYPT);
        }

        return $hash;
    }

    static public function verify($string, $hash) 
    {
        return password_verify($string, $hash);
    }
}