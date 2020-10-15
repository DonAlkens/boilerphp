<?php 

use App\Core\Database\Migration;

class CustomerTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("customers");

        $this->field("id")->bigIncrements();
        $this->field("firstname")->string()->null();
        $this->field("lastname")->string()->null();
        $this->field("email")->string()->unique();
        $this->field("password")->string();
        $this->field("sign_up_method")->integer();
        $this->field("verified")->boolean();
        $this->field("blocked")->boolean();
        $this->field("created_date")->timestamp();
        $this->field("updated_date")->timestamp();
        $this->field("updated_by")->integer();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("customers");
    }

}

