<?php 

use App\Core\Database\Migration;

class UserTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->field("id")->bigIncrements()->null();
        $this->field("firstname")->string()->null();
        $this->field("lastname")->string()->null();
        $this->field("email")->string()->unique();
        $this->field("password")->string();
        $this->field("is_admin")->boolean();
        $this->field("role_id")->integer()->foreign("roles", "id");
        $this->field("created_date")->timestamp();
        $this->field("updated_date")->timestamp();
        $this->field("last_updated_by")->integer()->foreign("users", "id")->cascade();

        $this->table("users");
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

