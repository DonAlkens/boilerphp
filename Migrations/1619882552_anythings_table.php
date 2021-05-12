<?php 

use App\Core\Database\Migration;

class AnythingsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function in() {

        $this->table("anythings");

        $this->id();
        $this->timestamps();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function out() {

        $this->dropIfExists("anythings");
    }

}

