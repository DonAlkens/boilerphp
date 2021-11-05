<?php

namespace App;

use App\Core\Database\Model;


class Category extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public function subs() {
        return $this->hasMultiple(SubCategory::class, ["category" => "id"]);
    }

}

?>