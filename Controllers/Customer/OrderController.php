<?php

namespace App\Action\Urls\Controllers\Customer;


use App\Action\Urls\Controllers\Controller;
use App\Core\Urls\Request;
use App\Customer;
use App\Collection;
use App\Order;
use Session;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class OrderController extends Controller {

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

    public function index() {
        
        return view("user/order/index");
    }

    public function success(Request $request) {

        $order = (new Order)->where("id", $request->param["order"])->get();
        if($order) {

            static::$data["order"] = $order;
            return view("user/order/success", static::$data);
        }

        return error404();
    }

}