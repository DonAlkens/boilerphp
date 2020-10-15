<?php

namespace App\Action\Urls\Controllers\Customer;


use App\Action\Urls\Controllers\Controller;
use App\Core\Urls\Request;
use Session;
use App\Customer;
use App\AddressBook;
use App\Collection;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class AddressController extends Controller {


    public static $data;


    public function __construct()
    {
        $location = str_replace("/", "_", (new Request("get"))->location());
        $this->hasAuthAccess("auth", "/sign-in/rd/".$location);

        $customer = Session::get("auth");
        $customer = (new Customer)->where('id', $customer)->get();

        $get_collections = (new Collection)->all();

        static::$data = [
            "collections" => $get_collections,
            "customer" => $customer
        ];

    }


    public function index() {

        static::$data["title"] = "Delivery Address";

        $address = (new AddressBook)->where(["is_default" => "1", "customer" => static::$data["customer"]->id])->all();
        static::$data["addresses"] = $address;
        $address_count = (new AddressBook)->where(["is_default" => "1", "customer" => static::$data["customer"]->id])->count();
        static::$data["address_count"] = $address_count;

        return view("user/address/index", static::$data);

    }


    public function add_new(Request $request) {

        static::$data["title"] = "Add New Delivery Address";

        if($request->method == "POST") {


        } 
        
        return view("user/address/add_address", static::$data);
    }

}