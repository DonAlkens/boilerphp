<?php

namespace App;

use App\Core\Database\Model;


class Role extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];

    public function permissions() {

        return $this->hasMultiple(RolePermissions::class, ["role_id" => "id"]);
    }

}

?>