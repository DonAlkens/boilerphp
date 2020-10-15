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
        $location = str_replace("/", "_", (new Request("get"))->location());
        $this->hasAuthAccess("auth", "/sign-in/rd/".$location);
        
        $customer = Session::get("auth");
        $customer = (new Customer)->where('id', $customer)->get();

        $all_orders = (new Order)->where("customer", $customer->id)->orderBy("id", "DESC")->all();
        $new_orders = (new Order)->where(["customer" => $customer->id, "status" => 1])->all();
        $confirmed_orders = (new Order)->where(["customer" => $customer->id, "status" => 2])->all();
        $completed_orders = (new Order)->where(["customer" => $customer->id, "status" => 6])->all();
        $returned_orders = (new Order)->where(["customer" => $customer->id, "status" => 7])->all();
        $cancelled_orders = (new Order)->where(["customer" => $customer->id, "status" => 8])->all();
        
        
        $get_collections = (new Collection)->all();
        static::$data = array( 
            "customer" => $customer,
            "collections" => $get_collections,
            "all_orders" =>  $all_orders,
            "new_orders" => $new_orders,
            "confirmed_orders" => $confirmed_orders,
            "completed_orders" => $completed_orders,
            "returned_orders" => $returned_orders,
            "cancelled_orders" => $cancelled_orders,
            "status" => [
                1 => ["name" => "Received", "message" => "Your order has been received."],
                2 => ["name" => "Confirmed", "message" => "Order has been confirmed and currently in process."],
                // 3 => ["name" => "Processing", "message" => "Your order is currently in proccess."],
                4 => ["name" => "Processed", "message" => "Order has been processed waiting to be shipped."],
                5 => ["name" => "Shipping", "message" => "Your order has been shipped will be delivered soon."],
                6 => ["name" => "Completed", "message" => "This order has been successfully delivered."],
                7 => ["name" => "Returned", "message" => "This order was returned."],
                8 => ["name" => "Cancelled", "message" => "This order has been cancelled."]
            ]
        );
    }

    public function index() {
        
        return view("user/order/index", static::$data);
    }

    public function success(Request $request) {

        $customer = static::$data["customer"]->id;
        $order = (new Order)->where(["id" => $request->param["order"], "customer" => $customer])->get();
        if($order) {

            static::$data["order"] = $order;
            return view("user/order/success", static::$data);
        }

        return error404();
    }

    public function details(Request $request) {

        $customer = static::$data["customer"]->id;
        $order = (new Order)->where(["id" => $request->param["order"], "customer" => $customer])->get();
        if($order) {

            static::$data["order"] = $order;
            return view("user/order/details", static::$data);
        }

        return error404();
    }

}