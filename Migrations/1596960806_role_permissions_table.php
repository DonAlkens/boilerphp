<?php 

use App\Core\Database\Migration;

class RolePermissionsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->field("id")->bigIncrements()->null();
        $this->field("role_id")->integer()->foreign("roles", "id")->cascade();
        $this->field("permission_id")->integer()->foreign("permissions", "id")->cascade();
        $this->field("created_date")->timestamp();

        $this->table("role_permissions");
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("role_permissions");
    }

}

