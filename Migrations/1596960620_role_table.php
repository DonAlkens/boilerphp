<?php 

use App\Core\Database\Migration;

class RoleTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("roles");
        $this->column("id")->id();
        $this->column("name")->string()->unique();
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

        $this->dropIfExists("roles");
    }

}

