<?php

namespace App;

use App\Core\Database\Model;


class Country extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public function dial() {
        return $this->hasOne(DialCode::class, ["id" => "phone_code"]);
    }

}

?>