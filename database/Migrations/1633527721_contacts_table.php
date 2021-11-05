<?php

use App\Core\Database\Migration\Diagram;
use App\Core\Database\Migration\Migration;
use App\Core\Database\Migration\Table;


class ContactsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function in() {

        Table::create("contacts", function(Diagram $diagram){

            $diagram->id();
            $diagram->column("name")->string();
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

        Table::dropIfExists("contacts");
    }

}



