<?php

namespace App;

use App\Core\Database\Model;


class Withdrawal extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public function vendor() {

        return $this->hasOne(User::class, ["id" => "vendor"]);
    }

}

?>