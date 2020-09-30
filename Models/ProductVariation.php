<?php

namespace App;

use App\Core\Database\Model;


class ProductVariation extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public $table = "product_variations";

    public function variation() {
        
        return $this->hasOne(Variation::class, ["id" => "variation"]);
    }

}

?>