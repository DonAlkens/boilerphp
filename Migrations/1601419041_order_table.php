<?php 

use App\Core\Database\Migration;

class OrderTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("orders");

        $this->field("id")->bigIncrements();
        $this->field("customer")->integer()->foreign("customers", "id");
        $this->field("address")->integer();
        $this->field("amount")->float();
        $this->field("delivery_period")->string();
        $this->field("shipping_fee")->float();
        $this->field("payment_method")->string();
        $this->field("payment_status")->integer();
        $this->field("status")->integer();
        $this->field("cancelled_date")->timestamp();
        $this->field("confirmed_date")->timestamp();
        $this->field("shipped_date")->timestamp();
        $this->field("completed_date")->timestamp();
        $this->field("returned_date")->timestamp();
        $this->field("created_date")->timestamp();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("orders");
    }

}

