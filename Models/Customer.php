<?php

namespace App;

use App\Core\Database\Model;


class Customer extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];

    public function new($name, $email, $password, $method) {

        $split_name = explode(" ", $name);

        $firstname = $split_name[0];
        $lastname = isset($split_name[1]) ? $split_name[1] : "";

        $create = $this->insert(array(
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "password" => $password,
            "sign_up_method" => $method
        ));

        if($create != null) {
            return $create;
        }

        return false;
    }

    public function exists($email) {
        
        if($this->where("email", $email)->get()) {
            return true;
        }

        return false;
    }

}

?>