<?php 

use App\Core\Database\Migration;

class ProductVariationTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("product_variations");
        $this->field("id")->bigIncrements();
        $this->field("product")->integer()->foreign("products", "id")->cascade();
        $this->field("variation")->integer();
        $this->field("value")->text();
        $this->field("price")->float();
        $this->field("quantity")->integer();
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

        $this->dropIfExists("product_variations");
    }

}

