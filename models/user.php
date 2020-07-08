<?php

/** 
 * creating a model class
 * it must extends the schema class
 * in order to use the dboperation methods in 
 * the model class
 * @example creating a model
 * class User extends Schema{
 *  #state the table name in the public $table variable
 *  #the model structure should be design 
 *  #and assign to the public $model variable
 * }
 * */

use App\Core\Database\Model;

class User extends Model {

    public $table = 'users';

    public $model = array(
        'sn' => 'increments',
        'user_id' => 'uniqueInteger',
        'fullname' => 'string',
        "username" => "string",
        'email' => 'uniqueString',
        'phone' => 'string',
        'password' => 'string',
        'is_admin' => 'bool',
        'blocked' => 'bool'
    );

    
    public function admin(){
        return $this->hasOne(Admin::class, "user_id");
    }

    public function address(){
        return $this->hasMultiple(Address::class, "user_id");
    }

}