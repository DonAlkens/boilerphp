<?php 

use App\Core\Database\Migration;

class SavedItemsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("saved_items");

        $this->field("id")->bigIncrements();
        $this->field("customer")->integer()->foreign("customers", "id");
        $this->field("product")->integer()->foreign("products", "id");
        $this->field("created_date")->timestamp();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("saved_items");
    }

}

