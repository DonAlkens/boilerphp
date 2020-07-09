<?php

/** 
 * creating a model class
 * it must extends the model class
 * in order to use the dboperation methods in 
 * the model class
 * @example creating a model
 * class User extends Model {}
 * */

use App\Core\Database\Model;

class Permission extends Model {

    public $table = 'permissions';

    public $model = array(
        'id' => 'bigIncrements',
        'name' => 'uniqueString'
    );

}