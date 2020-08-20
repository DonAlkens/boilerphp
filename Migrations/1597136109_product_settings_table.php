<?php 

use App\Core\Database\Migration;

class ProductSettingsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("product_settings");
        $this->field("id")->bigIncrements();
        $this->field("product")->integer()->foreign("products", "id")->cascade();
        $this->field("search_keywords")->text();
        $this->field("out_of_stock")->boolean();
        $this->field("featured")->boolean();
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

        $this->dropIfExists("product_settings");
    }

}

