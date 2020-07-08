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

class Review extends Model {

    public $table = 'reviews';

    public $model = array(
        'sn' => 'bigIncrements',
        'product_id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'message' => 'text'
    );

    public function product() {
        return $this->hasOne(Product::class, 'product_id');
    }

}