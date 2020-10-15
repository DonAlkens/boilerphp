<?php

namespace App\Action\Urls\Controllers;

use App\AddressBook;
use App\Core\Urls\Request;
use App\Collection;
use App\Customer;
use App\Order;
use App\SavedItem;
use Session;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class UserController extends Controller {

    public static $data;

    public function __construct()
    {
 
        $location = str_replace("/", "_", (new Request("get"))->location());
        $this->hasAuthAccess("auth", "/sign-in/rd/".$location);
        
        $customer = Session::get("auth");
        $customer = (new Customer)->where('id', $customer)->get();
        
        $new_orders = (new Order)->where(["customer" => $customer->id, "status" => 1])->count();
        $confirmed_orders = (new Order)->where(["customer" => $customer->id, "status" => 2])->count();
        $completed_orders = (new Order)->where(["customer" => $customer->id, "status" => 6])->count();
        $returned_orders = (new Order)->where(["customer" => $customer->id, "status" => 7])->count();
        
        $get_collections = (new Collection)->all();

        static::$data = array( 
            "customer" => $customer,
            "collections" => $get_collections,
            "new_orders" => $new_orders,
            "confirmed_orders" => $confirmed_orders,
            "completed_orders" => $completed_orders,
            "returned_orders" => $returned_orders
        );


        $address = (new AddressBook)->where(["is_default" => "1", "customer" => $customer->id])->all();
        $address_count = (new AddressBook)->where(["is_default" => "1", "customer" => $customer->id])->count();
        static::$data["addresses"] = $address;
        static::$data["address_count"] = $address_count;

        $saved_items = (new SavedItem)->where("customer", auth()->id)->orderBy("id", "DESC", 3)->all();
        static::$data["saved_items"] = $saved_items;
        
    }

    public function index()
    {
        return view("user/index", static::$data);
    }

}