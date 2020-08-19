<?php

namespace App;

use App\Core\Database\Model;
use Collator;

class Category extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public $table = "categories";

    public function creator() {
        return $this->hasOne(User::class, ["id" => "created_by"]);
    }

    public function updator() {
        return $this->hasOne(User::class, ["id" => "last_updated_by"]);
    }

    public function collection() {
        return $this->hasOne(Collection::class, ["id" => "collection"]);
    }

}

?>