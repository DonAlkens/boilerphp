<?php 

use App\Core\Database\Migration;

class ProductReviewsTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->table("product_reviews");

        $this->field("id")->bigIncrements();
        $this->field("product")->integer()->foreign("products", "id")->cascade();
        $this->field("fullname")->string();
        $this->field("email")->string();
        $this->field("rate")->string();
        $this->field("message")->string();
        $this->field("created_date")->timestamp();

        $this->sign();
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("product_reviews");
    }

}

