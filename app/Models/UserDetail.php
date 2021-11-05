<?php

namespace App;

use App\Core\Database\Model;


class UserDetail extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public function user() 
    {
        $this->hasOne(User::class, ["id" => "user_id"]);
    }

    public function address() 
    {
        return $this->hasOne(AddressBook::class, ["user_id" => "id"]);
    }

    public function updator() 
    {
        return $this->hasOne(User::class, ["id" => "updated_by"]);
    }

}

?>