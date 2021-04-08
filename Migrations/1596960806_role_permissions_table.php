<?php 

use App\Core\Database\Migration;

class RolePermissionsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("role_permissions");
        $this->column("id")->bigIncrements();
        $this->column("role_id")->bigInt()->foreign("roles", "id")->cascade();
        $this->column("permission_id")->bigInt()->foreign("permissions", "id")->cascade();
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

        $this->dropIfExists("role_permissions");
    }

}

