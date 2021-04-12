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
        return $this->hasOne(Role::class, ["id" => "role_id"]);
    }

}

?>