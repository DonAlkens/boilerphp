<?php 

use App\Core\Database\Migration;

class CartTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("carts");

        $this->field("id")->bigIncrements();
        $this->field("user")->integer()->foreign("users", "id")->cascade();
        $this->field("product")->integer()->foreign("products", "id")->cascade();
        $this->field("quantity")->integer();
        $this->field("variant")->integer();
        $this->field("created_date")->timestamp();
        $this->field("updated_date")->timestamp();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("carts");
    }

}

