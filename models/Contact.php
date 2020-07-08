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

class Contact extends Model {

    public $table = "contact";

    public $model = array(
        "sn" => "bigIncrements",
        "fullname" => "string",
        "email" => "string",
        "subject" => 'string',
        "message" => 'text'
    );

}