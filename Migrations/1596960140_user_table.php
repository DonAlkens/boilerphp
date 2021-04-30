<?php 

use App\Core\Database\Migration;

class UserTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function in() {

        $this->table("users");

        $this->id();
        $this->column("firstname")->string()->nullable();
        $this->column("lastname")->string()->nullable();
        $this->column("email")->string()->unique();
        $this->column("password")->string();
        $this->column("is_admin")->boolean();
        $this->column("role_id")->bigInteger()->foreign("roles");
        $this->column("verified")->boolean();
        $this->column("blocked")->boolean();
        $this->column("picture")->text();
        $this->timestamps();
        
        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function out() {

        $this->dropIfExists("users");
    }

}

