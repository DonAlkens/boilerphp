<?php 

use App\Core\Database\Migration;

class UserTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("users");
        $this->column("id")->bigIncrements()->nullable();
        $this->column("firstname")->string()->nullable();
        $this->column("lastname")->string()->nullable();
        $this->column("email")->string()->unique();
        $this->column("password")->string();
        $this->column("TFA")->boolean();
        $this->column("is_customer")->boolean();
        $this->column("is_vendor")->boolean();
        $this->column("is_admin")->boolean();
        $this->column("role")->integer();
        $this->column("sign_up_method")->integer();
        $this->column("verified")->boolean();
        $this->column("approved")->boolean();
        $this->column("blocked")->boolean();
        $this->column("picture")->text();
        $this->timestamps();
        $this->column("created_by")->integer();
        $this->column("last_updated_by")->integer();
        
        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("users");
    }

}

