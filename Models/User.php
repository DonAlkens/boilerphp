<?php

namespace App;

use App\Core\Database\Model;

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


class User extends Model {
    
    public function admin(){
        return $this->hasOne(Admin::class, ["admin_id" => "user_id"]);
    }

    public function address(){
        return $this->hasMultiple(Address::class, "user_id");
    }

}