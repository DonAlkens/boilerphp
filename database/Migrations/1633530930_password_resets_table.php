<?php

use App\Core\Database\Migration\Diagram;
use App\Core\Database\Migration\Migration;
use App\Core\Database\Migration\Table;


class PasswordResetsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function in() {

        Table::create("password_resets", function(Diagram $diagram){

            $diagram->id();
            $diagram->column("user_id")->integer();
            $diagram->column("code")->string();
            $diagram->column("status")->boolean();
            $diagram->column("expired")->boolean();
            $diagram->timestamps();

        });

    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function out() {

        Table::dropIfExists("password_resets");
    }

}



