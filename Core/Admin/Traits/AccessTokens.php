<?php

namespace App\Admin\Auth;

use App\Core\Database\Schema;
use App\Hashing\Hash;

trait AccessTokens
{ 
    
    protected $_table = 'auth_access_tokens';


    public function createAccessToken($name, $access = [], $token_user_id = null) {

        $token_type = get_class($this);
        $token = [
            'name' => $name,
            'token_type' => $token_type,
            'token_id' => $token_user_id ? $token_user_id : $this->id,
            'token' => hash('sha256', $this->genenrateToken(40)),
            'access' => json_encode($access)
        ];

        (new Schema)->table($this->_table)->create($token);
        $encode = (new Hash)->getEncodedBase($token['token']);
        return $encode;
    }

    // public function getToken() {
        
    //     $token_id = $this->id;
    //     $type = get_class($this);

    //     return (new Schema)->table($this->_table)->where([
    //         'token_id' => $token_id,
    //         'token_type' => $type
    //     ])->last();
    // }


    // public function getTokens() {
        
    //     $token_id = $this->id;
    //     $type = get_class($this);

    //     return (new Schema)->table($this->_table)->where([
    //         'token_id' => $token_id,
    //         'token_type' => $type
    //     ])->all();
    // }
    

    protected function genenrateToken($length = 16) {

        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string; 
    }
}
