<?php 

use App\Core\Database\Migration;

class VariationTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->field("id")->bigIncrements();
        $this->field("name")->string()->unique();
        $this->field("created_date")->timestamp();
        $this->field("created_by")->integer();
        $this->field("last_updated_date")->timestamp();
        $this->field("last_updated_by")->integer();


        $this->table("variations");
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("variations");
    }

}

