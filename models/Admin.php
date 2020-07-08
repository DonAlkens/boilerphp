<?php

namespace App;

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

class Admin extends Model {

    public $table = 'admins';
    
    
    public function user(){
        return $this->hasOne(User::class, "user_id");
    }

    public function role(){
        return $this->hasOne(Role::class, ["id" => "role_id"]);
    }

}