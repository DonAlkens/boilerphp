<?php 

use App\Core\Database\Migration;

class RolePermissionsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function in() {

        $this->table("role_permissions");

        $this->id();
        $this->column("role_id")->bigInteger()->foreign("roles", "id")->cascade();
        $this->column("permission_id")->bigInteger()->foreign("permissions", "id")->cascade();
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

        $this->dropIfExists("role_permissions");
    }

}

