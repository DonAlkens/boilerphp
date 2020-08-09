<?php 

use App\Core\Database\Migration;

class RoleTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->field("id")->bigIncrements()->null();
        $this->field("name")->string()->unique();
        $this->field("created_date")->timestamp();
        $this->field("updated_date")->timestamp();
        $this->field("created_by")->integer()->foreign("users", "id");
        $this->field("updated_by")->integer()->foreign("users", "id");

        $this->table("roles");
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

