<?php 

use App\Core\Database\Migration;

class TestTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("tests");

        $this->field("id")->bigIncrements();
        $this->field("name")->string();
        $this->field("created_date")->timestamp();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("tests");
    }

}

