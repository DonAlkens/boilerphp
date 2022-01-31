<?php

namespace App\Core\Database\Migration;

interface MigrationInterface {


    public static function create($name, $callback);

}