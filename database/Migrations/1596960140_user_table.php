<?php

use App\Core\Database\Migration\Diagram;
use App\Core\Database\Migration\Migration;
use App\Core\Database\Migration\Table;



class UserTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function in() {

        Table::create("users", function(Diagram $diagram){

            $diagram->id();
            $diagram->column("firstname")->string();
            $diagram->column("lastname")->string();
            $diagram->column("email")->string()->unique();
            $diagram->column("password")->string();
            $diagram->column("role_id")->bigInteger();
            $diagram->column("verified")->boolean();
            $diagram->timestamps();

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

