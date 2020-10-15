<?php

namespace App;

use App\Core\Database\Model;


class User extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public function role()
    {
        return $this->hasOne(Role::class, ["id" => "role"]);
    }

    public function address() 
    {
        return $this->hasOne(VendorAddress::class, ["user" => "id"]);
    }

    public function details() 
    {
        return $this->hasOne(VendorDetail::class, ["user" => "id"]);
    }

    public function wallet() 
    {
        return $this->hasOne("App\VendorWallet", ["vendor" => "id"]);
    }

}

?>