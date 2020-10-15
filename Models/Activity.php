<?php

namespace App;

use App\Core\Database\Model;


class Activity extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public function log($data) {

        $this->insert($data);
    }


}

?>