<?php 

use App\Core\Database\Migration;

class ProductTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->field("id")->bigIncrements();
        $this->field("name")->string();
        $this->field("slug")->string();
        $this->field("description")->longtext();
        $this->field("price")->string();
        $this->field("category")->integer();
        $this->field("sub_category")->integer();
        $this->field("created_date")->timestamp();
        $this->field("created_by")->integer();
        $this->field("last_updated_date")->timestamp();
        $this->field("last_updated_by")->integer();

        $this->table("products");
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("products");
    }

}

