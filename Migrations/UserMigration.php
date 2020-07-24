<?php 

use App\Core\Database\Migration;

class UserMigration extends Migration {

    /**
     * create table with included field
     */
    public function create() {

        $this->field("id")->bigIncrements()->null();
        $this->field("created_date")->timestamp();

        $this->table("users");
    }

    /**
     * drop table if table exists
     */
    public function drop() {

        $this->dropIfExists("users");
    }

}
