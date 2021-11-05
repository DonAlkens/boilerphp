<?php

namespace App;

use App\Core\Database\Model;


class Contact extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public function list() {
        $this->hasMultiple(ContactList::class, ["contact_id" => "id"]);
    }

}

?>