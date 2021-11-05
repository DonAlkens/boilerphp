<?php

namespace App;

use App\Core\Database\Model;


class User extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public function exists($email, $method = null) 
    {
        $params = ["email" => $email];
        if($method != null) {
            $params["sign_up_method"] = $method;
        }
        
        if($this->where($params)->get()) {
            return true;
        }
        return false;
    }

    public function name() {
        return $this->firstname." ".$this->lastname;
    }

    public function symbol() {
        return substr($this->firstname, 0, 1);
    }

    public function new($name, $email, $password) {

        $split_name = explode(" ", $name);

        $firstname = $split_name[0];
        $lastname = isset($split_name[1]) ? $split_name[1] : "";

        $data = array(
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "password" => $password,
            "role_id" => 4,
        );

        $create = $this->insert($data);

        if($create != null) {
            return $create;
        }

        return false;
    }

    public function details() 
    {
        return $this->hasOne(UserDetail::class, ["user_id" => "id"]);
    }
    
    public function role()
    {
        return $this->hasOne(Role::class, ["id" => "role_id"]);
    }

}

?>