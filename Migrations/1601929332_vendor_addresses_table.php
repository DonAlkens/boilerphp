<?php 

use App\Core\Database\Migration;

class VendorAddressesTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("vendor_addresses");

        $this->field("id")->bigIncrements();
        $this->field("user")->integer()->foreign("users", "id");
        $this->field("vendor")->integer()->foreign("vendor_details", "id");
        $this->field("street")->text();
        $this->field("additional_address")->text();
        $this->field("city")->string();
        $this->field("state")->string();
        $this->field("country")->string();
        $this->field("zip")->string();
        $this->field("created_date")->timestamp();
        $this->field("last_updated_date")->timestamp();
        $this->field("last_updated_by")->integer();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("vendor_addresses");
    }

}

