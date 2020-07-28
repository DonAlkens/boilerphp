<?php

namespace App\Helpers;

class Hash {

    static public function create($string) {
        return password_hash($string, PASSWORD_BCRYPT);
    }

    static public function verify($string, $hash) {
        return password_verify($string, $hash);
    }
}