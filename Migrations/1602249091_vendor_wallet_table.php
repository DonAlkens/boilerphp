<?php 

use App\Core\Database\Migration;

class VendorWalletTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("vendor_wallets");

        $this->field("id")->bigIncrements();
        $this->field("vendor")->integer()->foreign("users", "id");
        $this->field("balance")->float();
        $this->field("active")->boolean();
        $this->field("created_date")->timestamp();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("vendor_wallets");
    }

}

