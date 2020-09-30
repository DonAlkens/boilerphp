<?php

namespace App;

use App\Core\Database\Model;


class AddressBook extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public $table = "address_books";

}

?>