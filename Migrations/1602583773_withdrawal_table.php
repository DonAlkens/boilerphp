<?php 

use App\Core\Database\Migration;

class WithdrawalTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("withdrawals");

        $this->field("id")->bigIncrements();
        $this->field("vendor")->integer()->foreign("users", "id");
        $this->field("account_name")->string();
        $this->field("account_number")->string();
        $this->field("bank")->string();
        $this->field("amount")->float();
        $this->field("status")->boolean();
        $this->field("created_date")->timestamp();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("withdrawals");
    }

}

