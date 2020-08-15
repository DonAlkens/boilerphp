<?php 

use App\Core\Database\Migration;

class ProductSettingsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->field("id")->bigIncrements();
        $this->field("product")->integer()->foreign("products", "id")->cascade();
        $this->field("out_of_stock")->boolean();
        $this->field("created_date")->timestamp();
        $this->field("created_by")->integer();
        $this->field("last_updated_date")->timestamp();
        $this->field("last_updated_by")->integer();


        $this->table("productsettings");
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("productsettings");
    }

}

