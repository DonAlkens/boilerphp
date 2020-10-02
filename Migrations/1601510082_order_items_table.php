<?php 

use App\Core\Database\Migration;

class OrderItemsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("order_items");

        $this->field("id")->bigIncrements();
        $this->field("order")->integer()->foreign("orders", "id")->cascade();
        $this->field("product")->integer()->foreign("products", "id")->cascade();
        $this->field("quantity")->integer();
        $this->field("price")->float();
        $this->field("variant")->integer();
        $this->field("created_date")->timestamp();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("order_items");
    }

}

