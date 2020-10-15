<?php

namespace App\Action\Urls\Controllers\Vendor;


use App\Action\Urls\Controllers\Controller;
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

        // $this->hasAuthAccess("auth", "signin");

        // (new Door)->openWith("manage orders", function(){
        //     return content("Access Denied!. You have not being granted permission.");
        // });
        
    }

    public function pending(Request $request) {

        $data["title"] = "New Orders";
        $data["group"] = "pending";
        $data["heading"] = "New Orders";

        if(isset($request->shipped)) {
            $item = (new OrderItem)->where("id", $request->shipped)->get();
            if($item) {
                if($item->shipped != 1) {
                    $item->where("id", $request->shipped)->update(["shipped" => 1]);
                    $data["shipped"] = true;
                }
            }
        }

        return view("vendor/order/orders", $data);
    }

    public function shipped(Request $request) {

        $data["title"] = "Shipped Orders";
        $data["group"] = "shipped";
        $data["heading"] = "Shipped Orders";

        return view("vendor/order/orders", $data);
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

}