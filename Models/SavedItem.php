<?php

namespace App;

use App\Core\Database\Model;


class SavedItem extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public $table = "saved_items";


    public function product() {

        return $this->hasOne(Product::class, ["id" => "product"]);
    }

}

?>