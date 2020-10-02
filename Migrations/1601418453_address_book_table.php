<?php 

use App\Core\Database\Migration;

class AddressBookTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("address_books");

        $this->field("id")->bigIncrements();
        $this->field("customer")->integer()->foreign("customers", "id")->cascade();
        $this->field("firstname")->string();
        $this->field("lastname")->string();
        $this->field("street")->string();
        $this->field("additional_address")->string();
        $this->field("city")->string();
        $this->field("state")->string();
        $this->field("zip")->string();
        $this->field("phone")->string();
        $this->field("is_default")->boolean();
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

        $this->dropIfExists("address_books");
    }

}

