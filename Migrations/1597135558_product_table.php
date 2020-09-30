<?php 

use App\Core\Database\Migration;

class ProductTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("products");
        $this->field("id")->bigIncrements();
        $this->field("name")->string();
        $this->field("slug")->string();
        $this->field("brand")->string();
        $this->field("color")->string();
        $this->field("description")->longtext();
        $this->field("price")->float();
        $this->field("quantity")->integer();
        $this->field("discount")->integer();
        $this->field("discount_price")->float();
        $this->field("collection")->integer();
        $this->field("category")->integer();
        $this->field("sub_category")->integer();
        $this->field("sponsored")->boolean();
        $this->field("created_date")->timestamp();
        $this->field("created_by")->integer();
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

        $this->dropIfExists("products");
    }

}

