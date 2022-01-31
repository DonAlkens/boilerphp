<?php

use App\Core\Database\Seeder;
use App\User;

class Test extends Seeder {

    /**
     * seeds database table
     * 
     * @return void
     */
    public function run() {

        (new User)->insert(["email" => "you@example.com"]);

    }

}



