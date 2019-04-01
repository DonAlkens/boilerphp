<?php

use App\Core\Database\Schema;

class Migration extends Schema {
    public function Register($model){
        $model = new $model();
        $model->table($model->table, $model->model);
        $model->save();
    }
}