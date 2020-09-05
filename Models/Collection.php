<?php

namespace App;

use App\Core\Database\Model;


class Collection extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];

    public function last_product() {
        return $this->products()[0];
    }

    public function products() {
        return $this->hasMultiple(Product::class, ["collection" => "id"]);
    }

    public function categories() {
        return $this->hasMultiple(Category::class, ["collection" => "id"]);
    }

    public function creator() {
        return $this->hasOne(User::class, ["id" => "created_by"]);
    }

    public function updator() {
        return $this->hasOne(User::class, ["id" => "last_updated_by"]);
    }

}

?>