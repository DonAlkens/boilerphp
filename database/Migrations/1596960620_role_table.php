<?php

use App\Core\Database\Migration\Diagram;
use App\Core\Database\Migration\Migration;
use App\Core\Database\Migration\Table;

class RoleTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function in() {

        Table::create("roles", function(Diagram $diagram) {

            $diagram->id();
            $diagram->column("name")->string()->unique();
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

        Table::dropIfExists("roles");
    }

}

