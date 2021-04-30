<?php 

use App\Core\Database\Migration;

class RoleTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function in() {

        $this->table("roles");

        $this->id();
        $this->column("name")->string()->unique();
        $this->column("created_by")->bigInteger()->foreign("users", "id");
        $this->column("updated_by")->bigInteger()->foreign("users", "id");
        $this->timestamps();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function out() {

        $this->dropIfExists("roles");
    }

}

