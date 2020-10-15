<?php 

use App\Core\Database\Migration;

class VendorWalletTransactionsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("vendor_wallet_transactions");

        $this->field("id")->bigIncrements();
        $this->field("wallet")->integer()->foreign("vendor_wallets", "id");
        $this->field("reference")->float();
        $this->field("credit")->float();
        $this->field("debit")->float();
        $this->field("description")->text();
        $this->field("created_date")->timestamp();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("vendor_wallet_transactions");
    }

}

