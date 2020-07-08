<?php 

use App\Core\Database\Migration;

class CreateUsersTable extends Migration {

    public function create() {

        $this->table("users", array (
            "id" => $this->bigIncrements(),
            "created_date" => $this->timestamp()
        ));
    }

    public function drop() {
        $this->dropIfExists("users");
    }

}