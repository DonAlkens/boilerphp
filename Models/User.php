<?php

namespace App;

use App\Core\Database\Model;


class User extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];

    public function exists($email) 
    {
        if($this->where(["email" => $email, "sign_up_method" => 1])->get()) {
            return true;
        }
        return false;
    }

    public function role()
    {
        return $this->hasOne(Role::class, ["id" => "role"]);
    }

    public function updator() {
        return $this->hasOne(User::class, ["id" => "last_updated_by"]);
    }

    public function name() {
        return $this->firstname." ".$this->lastname;
    }

    public function symbol() {
        return substr($this->firstname, 0, 1);
    }

    public function new($name, $email, $password, $method) {

        $split_name = explode(" ", $name);

        $firstname = $split_name[0];
        $lastname = isset($split_name[1]) ? $split_name[1] : "";

        $create = $this->insert(array(
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "password" => $password,
            "is_customer" => 1,
            "role" => 4,
            "sign_up_method" => $method
        ));

        if($create != null) {
            return $create;
        }

        return false;
    }
}

?>