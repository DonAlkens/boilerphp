<?php 

use App\Core\Database\Migration;

class UserTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->field("id")->bigIncrements()->null();
        $this->field("created_date")->timestamp();

        $this->table("users");
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("users");
    }

}

