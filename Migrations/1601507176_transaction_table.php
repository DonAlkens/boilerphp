<?php 

use App\Core\Database\Migration;

class TransactionTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("transactions");

        $this->field("id")->bigIncrements();
        $this->field("reference")->string();
        $this->field("customer")->integer()->foreign("customers", "id")->cascade();
        $this->field("order")->integer()->foreign("orders", "id")->cascade();
        $this->field("amount")->float();
        $this->field("payment_method")->string();
        $this->field("status")->integer();
        $this->field("created_date")->timestamp();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("transactions");
    }

}

