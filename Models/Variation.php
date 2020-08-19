<?php

namespace App;

use App\Core\Database\Model;


class Variation extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];
    

    public function creator() {
        return $this->hasOne(User::class, ["id" => "created_by"]);
    }

    public function updator() {
        return $this->hasOne(User::class, ["id" => "last_updated_by"]);
    }

}

?>