<?php

namespace App\Action\Urls\Controllers\Api;


use App\Action\Urls\Controllers\Controller;
use App\Activity;
use App\Admin\Door;
use App\Core\Urls\Request;
use App\Order;
use App\OrderItem;
use App\VendorWallet;
use Auth;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class OrderController extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth", "/signin");

        (new Door)->openWith("manage orders", function(){

            $response = array(
                "status" => 200,
                "success" => false,
                "error" => array(
                    "message" => "Access denied: You do not have permission to manage orders"
                )
            );
            return Json($response);
        });
    }

    public function new() {

        $orders = (new Order)->where("status", 1)->orderBy("id", "DESC")->all();
        $list = array();

        if($orders) {

            foreach($orders as $order) {

                $payment_status = "";
                if($order->payment_status == 316) {
                    $payment_status = "Pending";
                } 
                else if($order->payment_status == 111) {
                    $payment_status = "Successful";
                }
                else if($order->payment_status == 901) {
                    $payment_status = "Failed";
                }
                else if($order->payment_status == 579) {
                    $payment_status = "Cancelled";
                }

                $amount = "<span>&#8358<span>".$order->amount;
                $shipping_fee = "<span>&#8358<span>".$order->shipping_fee;
                $data = array(
                    $order->id,
                    $order->customer()->email,
                    $amount,
                    $shipping_fee,
                    $order->payment_method,
                    $payment_status,
                    $order->created_date
                );


                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function confirmed() {

        $orders = (new Order)->where("status", 2)->orderBy("id", "DESC")->all();
        $list = array();

        if($orders) {

            foreach($orders as $order) {

                $payment_status = "";
                if($order->payment_status == 316) {
                    $payment_status = "Pending";
                } 
                else if($order->payment_status == 111) {
                    $payment_status = "Successful";
                }
                else if($order->payment_status == 901) {
                    $payment_status = "Failed";
                }
                else if($order->payment_status == 579) {
                    $payment_status = "Cancelled";
                }

                $amount = "<span>&#8358<span>".$order->amount;
                $shipping_fee = "<span>&#8358<span>".$order->shipping_fee;
                $data = array(
                    $order->id,
                    $order->customer()->email,
                    $amount,
                    $shipping_fee,
                    $order->payment_method,
                    $payment_status,
                    $order->created_date
                );


                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function processed() {

        $orders = (new Order)->where("status", 4)->orderBy("id", "DESC")->all();
        $list = array();

        if($orders) {

            foreach($orders as $order) {

                $payment_status = "";
                if($order->payment_status == 316) {
                    $payment_status = "Pending";
                } 
                else if($order->payment_status == 111) {
                    $payment_status = "Successful";
                }
                else if($order->payment_status == 901) {
                    $payment_status = "Failed";
                }
                else if($order->payment_status == 579) {
                    $payment_status = "Cancelled";
                }

                $amount = "<span>&#8358<span>".$order->amount;
                $shipping_fee = "<span>&#8358<span>".$order->shipping_fee;
                $data = array(
                    $order->id,
                    $order->customer()->email,
                    $amount,
                    $shipping_fee,
                    $order->payment_method,
                    $payment_status,
                    $order->created_date
                );


                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function shipped() {

        $orders = (new Order)->where("status", 5)->orderBy("id", "DESC")->all();
        $list = array();

        if($orders) {

            foreach($orders as $order) {

                $payment_status = "";
                if($order->payment_status == 316) {
                    $payment_status = "Pending";
                } 
                else if($order->payment_status == 111) {
                    $payment_status = "Successful";
                }
                else if($order->payment_status == 901) {
                    $payment_status = "Failed";
                }
                else if($order->payment_status == 579) {
                    $payment_status = "Cancelled";
                }

                $amount = "<span>&#8358<span>".$order->amount;
                $shipping_fee = "<span>&#8358<span>".$order->shipping_fee;
                $data = array(
                    $order->id,
                    $order->customer()->email,
                    $amount,
                    $shipping_fee,
                    $order->payment_method,
                    $payment_status,
                    $order->created_date
                );


                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function completed() {

        $orders = (new Order)->where("status", 6)->orderBy("id", "DESC")->all();
        $list = array();

        if($orders) {

            foreach($orders as $order) {

                $payment_status = "";
                if($order->payment_status == 316) {
                    $payment_status = "Pending";
                } 
                else if($order->payment_status == 111) {
                    $payment_status = "Successful";
                }
                else if($order->payment_status == 901) {
                    $payment_status = "Failed";
                }
                else if($order->payment_status == 579) {
                    $payment_status = "Cancelled";
                }

                $amount = "<span>&#8358<span>".$order->amount;
                $shipping_fee = "<span>&#8358<span>".$order->shipping_fee;
                $data = array(
                    $order->id,
                    $order->customer()->email,
                    $amount,
                    $shipping_fee,
                    $order->payment_method,
                    $payment_status,
                    $order->created_date
                );


                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function returned() {

        $orders = (new Order)->where("status", 7)->orderBy("id", "DESC")->all();
        $list = array();

        if($orders) {

            foreach($orders as $order) {

                $payment_status = "";
                if($order->payment_status == 316) {
                    $payment_status = "Pending";
                } 
                else if($order->payment_status == 111) {
                    $payment_status = "Successful";
                }
                else if($order->payment_status == 901) {
                    $payment_status = "Failed";
                }
                else if($order->payment_status == 579) {
                    $payment_status = "Cancelled";
                }

                $amount = "<span>&#8358<span>".$order->amount;
                $shipping_fee = "<span>&#8358<span>".$order->shipping_fee;
                $data = array(
                    $order->id,
                    $order->customer()->email,
                    $amount,
                    $shipping_fee,
                    $order->payment_method,
                    $payment_status,
                    $order->created_date
                );


                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function cancelled() {

        $orders = (new Order)->where("status", 8)->orderBy("id", "DESC")->all();
        $list = array();

        if($orders) {

            foreach($orders as $order) {

                $payment_status = "";
                if($order->payment_status == 316) {
                    $payment_status = "Pending";
                } 
                else if($order->payment_status == 111) {
                    $payment_status = "Successful";
                }
                else if($order->payment_status == 901) {
                    $payment_status = "Failed";
                }
                else if($order->payment_status == 579) {
                    $payment_status = "Cancelled";
                }

                $amount = "<span>&#8358<span>".$order->amount;
                $shipping_fee = "<span>&#8358<span>".$order->shipping_fee;
                $data = array(
                    $order->id,
                    $order->customer()->email,
                    $amount,
                    $shipping_fee,
                    $order->payment_method,
                    $payment_status,
                    $order->created_date
                );


                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function confirm(Request $request) {

        $data = array(
            "status" => 2, 
            "confirmed_date" => $request->timestamp()
        );

        $confirm = (new Order)->where("id", $request->id)->update($data);
        if($confirm) {

            (new Activity)->log(
                ["user" => Auth::user()->id, 
                "is_order" => $request->id, 
                "description" => Auth::user()->email. " Confirmed this order."
            ]);

            $response = [
                "status" => 200,
                "success" => true,
                "message" => "Order has been successfully confirmed."
            ];

            return Json($response);
        }

        $response = [
            "status" => 200,
            "success" => false,
            "error" => ["message" => "Error occured while confirming this order. Please try again"]
        ];

        return Json($response);

    }

    public function process(Request $request) {

        $data = array(
            "status" => 4, 
            "confirmed_date" => $request->timestamp()
        );

        $confirm = (new Order)->where("id", $request->id)->update($data);
        if($confirm) {

            (new Activity)->log(
                ["user" => Auth::user()->id, 
                "is_order" => $request->id, 
                "description" => Auth::user()->email. " completed order processing."
            ]);

            $response = [
                "status" => 200,
                "success" => true,
                "message" => "Order has been successfully processed."
            ];



            return Json($response);
        }

        $response = [
            "status" => 200,
            "success" => false,
            "error" => ["message" => "Unable to marked this order processed. Plese try again."]
        ];

        return Json($response);

    }

    public function ship(Request $request) {

        $data = array(
            "status" => 5, 
            "shipped_date" => $request->timestamp()
        );

        $confirm = (new Order)->where("id", $request->id)->update($data);
        if($confirm) {

            (new Activity)->log(
                ["user" => Auth::user()->id, 
                "is_order" => $request->id, 
                "description" => Auth::user()->email. " marked order to be shipped."
            ]);

            $response = [
                "status" => 200,
                "success" => true,
                "message" => "Order has been successfully shipped."
            ];



            return Json($response);
        }

        $response = [
            "status" => 200,
            "success" => false,
            "error" => ["message" => "Unable to marked this order shipped. Plese try again."]
        ];

        return Json($response);

    }

    public function complete(Request $request) {

        $data = array(
            "status" => 6, 
            "completed_date" => $request->timestamp()
        );

        $order = (new Order)->where("id", $request->id)->get();
        if($order) {

            foreach($order->items() as $item) {

                $one_third = (1.3 / 100) * (float) $item->price;
                $vendor_credit = (float)($item->price * $item->quantity) - (float)($one_third * $item->quantity);

                $vendor = $item->product()->creator()->id;
                $get_vendor_wallet = (new VendorWallet)->where("vendor", $vendor)->get();

                $balance = ($get_vendor_wallet->balance + $vendor_credit);
                $get_vendor_wallet->where("vendor", $vendor)->update(["balance" => $balance]);

            }            

            $confirm = (new Order)->where("id", $request->id)->update($data);
            if($confirm) {
    
                (new Activity)->log(
                    ["user" => Auth::user()->id, 
                    "is_order" => $request->id, 
                    "description" => Auth::user()->email. " marked order to be completed."
                ]);
    
                $response = [
                    "status" => 200,
                    "success" => true,
                    "message" => "Order has been successfully completed."
                ];
    
    
    
                return Json($response);
            }

        }

        $response = [
            "status" => 200,
            "success" => false,
            "error" => ["message" => "Unable to complete order. Plese try again."]
        ];

        return Json($response);

    }

    public function return(Request $request) {

        $data = array(
            "status" => 7, 
            "returned_date" => $request->timestamp()
        );

        $confirm = (new Order)->where("id", $request->id)->update($data);
        if($confirm) {

            (new Activity)->log(
                ["user" => Auth::user()->id, 
                "is_order" => $request->id, 
                "description" => Auth::user()->email. " marked order to be returned."
            ]);

            $response = [
                "status" => 200,
                "success" => true,
                "message" => "Order has been successfully returned."
            ];



            return Json($response);
        }

        $response = [
            "status" => 200,
            "success" => false,
            "error" => ["message" => "Unable to return order. Plese try again."]
        ];

        return Json($response);

    }

    public function cancel(Request $request) {

        $data = array(
            "status" => 8, 
            "cancelled_date" => $request->timestamp()
        );

        $confirm = (new Order)->where("id", $request->id)->update($data);
        if($confirm) {

            (new Activity)->log(
                ["user" => Auth::user()->id, 
                "is_order" => $request->id, 
                "description" => Auth::user()->email. " cancelled this order."
            ]);

            $response = [
                "status" => 200,
                "success" => true,
                "message" => "Order has been cancelled."
            ];



            return Json($response);
        }

        $response = [
            "status" => 200,
            "success" => false,
            "error" => ["message" => "Unable to cancel order. Plese try again."]
        ];

        return Json($response);

    }

    public function get_confirmed_order_items() {

        $items = (new OrderItem)->where("confirmed", 1)->orderBy("id", "DESC")->all();
        $list = array();

        if($items) {
            foreach($items as $item) {

                $variant = "-";
                if($item->variation()) {
                    $variant = $item->variation()->variant;
                }
                
                $product = '<img src="/src/images/'. $item->product()->images()->main .'" alt="'. $item->product()->name .'"> '.$item->product()->name;
                $amount = "&#8358;".number_format($item->price);
                $data = array(
                    $item->id,
                    $item->order()->id,
                    $product,
                    $amount,
                    $variant,
                    $item->quantity,
                    $item->created_date
                );

                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function get_shipped_order_items() {

        $items = (new OrderItem)->where(["shipped" => 1, "confirmed" => 0])->orderBy("id", "DESC")->all();
        $list = array();

        if($items) {
            foreach($items as $item) {

                $variant = "-";
                if($item->variation()) {
                    $variant = $item->variation()->variant;
                }
                
                $product = '<img src="/src/images/'. $item->product()->images()->main .'" alt="'. $item->product()->name .'"> '.$item->product()->name;
                $amount = "&#8358;".number_format($item->price);
                $data = array(
                    $item->id,
                    $item->order()->id,
                    $product,
                    $amount,
                    $variant,
                    $item->quantity,
                    $item->created_date
                );

                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function get_pending_order_items() {

        $items = (new OrderItem)->where(["shipped" => 0, "confirmed" => 0])->orderBy("id", "DESC")->all();
        $list = array();

        if($items) {
            foreach($items as $item) {

                $variant = "-";
                if($item->variation()) {
                    $variant = $item->variation()->variant;
                }
                
                $product = '<img src="/src/images/'. $item->product()->images()->main .'" alt="'. $item->product()->name .'"> '.$item->product()->name;
                $amount = "&#8358;".number_format($item->price);
                $data = array(
                    $item->id,
                    $item->order()->id,
                    $product,
                    $amount,
                    $variant,
                    $item->quantity,
                    $item->created_date
                );

                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function get_vendor_pending_orders() {

        $items = (new OrderItem)->where(["vendor" => Auth::user()->id, "shipped" => 0])->all();
        $list = array();

        if($items) {
            foreach($items as $item) {

                $variant = "-";
                if($item->variation()) {
                    $variant = $item->variation()->variant;
                }
                
                $product = '<img src="/src/images/'. $item->product()->images()->main .'" alt="'. $item->product()->name .'"> '.$item->product()->name;
                $amount = "&#8358;".number_format($item->price);
                $data = array(
                    $item->id,
                    $product,
                    $amount,
                    $variant,
                    $item->quantity,
                    $item->created_date
                );

                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function get_vendor_shipped_orders() {

        $items = (new OrderItem)->where(["vendor" => Auth::user()->id, "shipped" => 1])->all();
        $list = array();

        if($items) {
            foreach($items as $item) {

                $variant = "-";
                if($item->variation()) {
                    $variant = $item->variation()->variant;
                }
                
                $product = '<img src="/src/images/'. $item->product()->images()->main .'" alt="'. $item->product()->name .'"> '.$item->product()->name;
                $amount = "&#8358;".number_format($item->price);

                $confirmed = '<span class="badge badge-warning">Awaiting</span>';
                if($item->confirmed) {
                    $confirmed = '<span class="badge badge-success">Confirmed</span>';
                }

                $data = array(
                    $item->id,
                    $product,
                    $amount,
                    $variant,
                    $item->quantity,
                    $confirmed,
                    $item->created_date
                );

                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function get_item(Request $request) {

        $item = (new OrderItem)->where("id", $request->param["id"])->get();
        if($item) {
            $details = array(
                "id" => $item->id,
                "created_date" => $item->created_date
            );

            return Json($details);
        }

        return null;
    }
}