<?php

use App\Core\Database\Migration\Diagram;
use App\Core\Database\Migration\Migration;
use App\Core\Database\Migration\Table;


class AlterUsersTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function in() {

        Table::modify("users", function(Diagram $diagram){
            $diagram->addColumn("role_id")->bigInteger();
        });

    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function out() {

        Table::dropIfExists("users");
    }

}



