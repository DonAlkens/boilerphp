<?php

namespace App;

use App\Core\Database\Model;


class PasswordReset extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public $table = "password_resets";

    
    public function __construct()
    {
        parent::__construct();
        $this->db("wearslot");
    }
}

?>