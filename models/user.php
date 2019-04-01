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

use App\Core\Database\Schema;

class User extends Schema{

    public $table = 'users';

    private $user_id;
    private $firstname;
    private $lastname;
    private $email;
    private $phone;
    private $password;


    public $model = array(
        'user_id' => 'id',
        'firstname' => 'string',
        'lastname' => 'string',
        'email' => 'email',
        'phone' => 'string',
        'address' => 'text',
        'city' => 'string',
        'state' => 'string',
        'country' => 'string',
        'zip_code' => 'int',
        'profile_image' => 'string',
        'password' => 'string'
    );

}