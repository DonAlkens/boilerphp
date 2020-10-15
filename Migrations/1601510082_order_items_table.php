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
        $this->field("vendor")->integer()->foreign("users", "id");
        $this->field("order")->integer()->foreign("orders", "id");
        $this->field("product")->integer()->foreign("products", "id");
        $this->field("quantity")->integer();
        $this->field("price")->float();
        $this->field("variant")->integer();
        $this->field("shipped")->boolean();
        $this->field("confirmed")->boolean();
        $this->field("confirmed_by")->integer();
        $this->field("confirmed_date")->timestamp();
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

