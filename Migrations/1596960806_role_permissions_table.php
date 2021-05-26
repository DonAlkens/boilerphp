<?php 

use App\Core\Database\Migration\Diagram;
use App\Core\Database\Migration\Migration;
use App\Core\Database\Migration\Table;

class RolePermissionsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function in() {

        Table::create("role_permissions", function(Diagram $diagram){

            $diagram->id();
            $diagram->column("role_id")->bigInteger()->foreign("roles", "id")->cascade();
            $diagram->column("permission_id")->bigInteger()->foreign("permissions", "id")->cascade();
            $diagram->column("created_by")->bigInteger()->foreign("users", "id");
            $diagram->column("updated_by")->bigInteger()->foreign("users", "id");
            $diagram->timestamps();

        });
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function out() {

        Table::dropIfExists("role_permissions");
    }

}

