<?php 

use App\Core\Database\Migration;

class ActivityTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("activitys");

        $this->field("id")->bigIncrements();
        $this->field("user")->integer()->foreign("users", "id");
        $this->field("description")->longtext();
        $this->field("is_order")->integer();
        $this->field("is_product")->integer();
        $this->field("is_variation")->integer();
        $this->field("is_collection")->integer();
        $this->field("is_category")->integer();
        $this->field("created_date")->timestamp();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("activitys");
    }

}

