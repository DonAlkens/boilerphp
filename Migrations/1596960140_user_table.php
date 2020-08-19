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
        $this->field("id")->bigIncrements()->null();
        $this->field("firstname")->string()->null();
        $this->field("lastname")->string()->null();
        $this->field("email")->string()->unique();
        $this->field("password")->string();
        $this->field("is_admin")->boolean();
        $this->field("role")->integer()->foreign("roles", "id")->cascade();
        $this->field("created_date")->timestamp();
        $this->field("updated_date")->timestamp();
        $this->field("updated_by")->integer();

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

