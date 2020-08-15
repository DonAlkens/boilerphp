<?php 

use App\Core\Database\Migration;

class CategoriesTable extends Migration {

    /**
     * creates database table
     * 
     * @return void
     */
    public function create() {

        $this->field("id")->bigIncrements();
        $this->field("name")->string()->unique();
        $this->field("slug")->string();
        $this->field("collection")->integer()->foreign("collections", "id")->cascade();
        $this->field("created_date")->timestamp();
        $this->field("created_by")->integer();
        $this->field("last_updated_date")->timestamp();
        $this->field("last_updated_by")->integer();

        $this->table("categories");
    }

    /**
     * drop database table
     * 
     * @return void
     */
    public function drop() {

        $this->dropIfExists("categories");
    }

}

