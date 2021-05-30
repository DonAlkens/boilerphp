<?php

namespace App;

use App\Core\Database\Model;


class Role extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public function creator() {
        return $this->hasOne(User::class, ["id" => "created_by"]);
    }

    public function updator() {
        return $this->hasOne(User::class, ["id" => "updated_by"]);
    }

    public function permissions() {
        return $this->hasMultiple(RolePermissions::class, ["role_id" => "id"]);
    }

}

?>