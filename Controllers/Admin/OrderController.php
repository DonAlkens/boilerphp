<?php

namespace App\Action\Urls\Controllers\Admin;


use App\Action\Urls\Controllers\Controller;
use App\Activity;
use App\Admin\Door;
use App\Core\Urls\Request;
use App\Order;
use App\OrderItem;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class OrderController extends Controller {


    public function __construct() {

        $this->hasAuthAccess("auth", "signin");

        (new Door)->openWith("manage orders", function(){
            return content("Access Denied!. You have not being granted permission.");
        });
        
    }

    public function index() {

        return view("order/index");
    }

    public function create(Request $request) {


    }

    public function new() {

        $data["group"] = "new";
        $data["heading"] = "Recent Orders";
        return view("admin/order/orders", $data);
    }

    public function confirmed() {

        $data["group"] = "confirmed";
        $data["heading"] = "Confirmed Orders";
        return view("admin/order/orders", $data);
        
    }

    public function processed() {

        $data["group"] = "processed";
        $data["heading"] = "Processed Orders";
        return view("admin/order/orders", $data);
        
    }

    public function shipped() {

        $data["group"] = "shipped";
        $data["heading"] = "Shipped Orders";

        return view("admin/order/orders", $data);
        
    }

    public function completed() {

        $data["group"] = "completed";
        $data["heading"] = "Completed Orders";
        return view("admin/order/orders", $data);
    }

    public function cancelled() {

        $data["group"] = "cancelled";
        $data["heading"] = "Cancelled Orders";
        return view("admin/order/orders", $data);
        
    }

    public function returned() {

        $data["group"] = "returned";
        $data["heading"] = "Returned Orders";
        return view("admin/order/orders", $data);
        
    }


    public function details(Request $request) {

        if(isset($request->param["order"])) {
            $id = $request->param["order"];
            $order = (new Order)->where("id", $id)->get();

            if($order) {

                $data["order"] = $order;
                return view("admin/order/details", $data);
            }
        }

        return error404();
    }

    public function confirmed_items() {

        $data["group"] = "confirmed";
        $data["heading"] = "Confirmed Items";
        return view("admin/order/order-items", $data);
    }

    public function shipped_items(Request $request) {

        $data["group"] = "shipped";
        $data["heading"] = "Shipped Items";

        if($request->method == "POST") {

            if(isset($request->confirm)) {
                
                $item = (new OrderItem)->where("id", $request->confirm)->get();
                if($item && $item->confirmed != 1) {
                    $update = [
                        "confirmed" => 1,
                        "confirmed_by" => auth()->id
                    ];

                    $item->where("id", $request->confirm)->update($update);

                    (new Activity)->log(
                        ["user" => auth()->id, 
                        "is_order" => $item->order, 
                        "description" => auth()->email. " confirmed an item with ID: $item->id"
                    ]);

                    $data["confirmed"] = true;

                }

            }

        }

        return view("admin/order/order-items", $data);
    }

    public function pending_items() {

        $data["group"] = "pending";
        $data["heading"] = "Pending Items";
        return view("admin/order/order-items", $data);
    }

}