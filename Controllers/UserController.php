<?php

namespace App\Action\Urls\Controllers;


use App\Core\Urls\Request;
use App\Collection;
use App\Customer;
use Session;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class UserController extends Controller {

    public static $data;

    public function __construct()
    {
        $request = new Request("get");
        $location = str_replace("/", "_", $request->location());

        $this->hasAuthAccess("auth", "/sign-in/rd/".$location);

        
        
        $customer = Session::get("auth");
        $customer = (new Customer)->where('id', $customer)->get();
        
        
        $get_collections = (new Collection)->all();
        static::$data = array( 
            "customer" => $customer,
            "collections" => $get_collections
        );
    }

    public function index()
    {
        return view("user/index", static::$data);
    }

}