<?php 

use App\Core\Database\Migration;

class ProductVariationOptionsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("product_variation_options");

        $this->field("id")->bigIncrements();
        $this->field("product")->integer()->foreign("products", "id")->cascade();
        $this->field("combined")->integer();
        $this->field("name")->text();
        $this->field("price")->float();
        $this->field("quantity")->integer();
        $this->field("sold_out")->boolean();
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

        $this->dropIfExists("product_variation_options");
    }

}

