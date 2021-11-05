<?php

use App\Core\Database\Migration\Diagram;
use App\Core\Database\Migration\Migration;
use App\Core\Database\Migration\Table;


class UserDetailsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function in() {

        Table::create("user_details", function(Diagram $diagram){

            $diagram->id();
            $diagram->column("user_id")->bigInteger();
            $diagram->column("country")->bigInteger();
            $diagram->column("gender")->string();
            $diagram->column("birthday")->string();
            $diagram->timestamps();

        });

    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function out() {

        Table::dropIfExists("user_details");
    }

}



