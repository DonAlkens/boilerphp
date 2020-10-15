<?php 

use App\Core\Database\Migration;

class VendorDetailsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("vendor_details");

        $this->field("id")->bigIncrements();
        $this->field("user")->integer()->foreign("users", "id");
        $this->field("vendor_name")->string();
        $this->field("phone")->string();
        $this->field("website")->string();
        $this->field("own_brand")->boolean();
        $this->field("product_category")->integer();
        $this->field("means_of_knowing")->text();
        $this->field("referral_id")->integer()->unique();
        $this->field("referred_by")->integer();
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

        $this->dropIfExists("vendor_details");
    }

}

