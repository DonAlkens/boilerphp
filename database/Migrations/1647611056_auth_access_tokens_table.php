<?php

use App\Core\Database\Migration\Diagram;
use App\Core\Database\Migration\Migration;
use App\Core\Database\Migration\Table;


class AuthAccessTokensTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function in() {

        Table::create("auth_access_tokens", function(Diagram $diagram){

            $diagram->id();
            $diagram->column('name')->string();
            $diagram->column('token_type')->string();
            $diagram->column('token_id')->bigInteger();
            $diagram->column('token')->string();
            $diagram->column('access')->string();
            $diagram->column('last_used_date')->timestamp()->nullable();
            $diagram->timestamps();

        });

    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function out() {

        Table::dropIfExists("auth_access_tokens");
    }

}



