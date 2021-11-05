<?php

use App\Core\Database\Migration\Diagram;
use App\Core\Database\Migration\Migration;
use App\Core\Database\Migration\Table;


class ContactListsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function in() {

        Table::create("contact_lists", function(Diagram $diagram){

            $diagram->id();
            $diagram->column("contact_id")->bigInteger();
            $diagram->column("name")->string();
            $diagram->column("email")->string();
            $diagram->column("created_by")->bigInteger();
            $diagram->column("updated_by")->bigInteger();
            $diagram->timestamps();

        });

    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function out() {

        Table::dropIfExists("contact_lists");
    }

}



