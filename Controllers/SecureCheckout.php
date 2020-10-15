<?php

namespace App\Action\Urls\Controllers;


use App\Core\Urls\Request;
use App\AddressBook;
use App\Cart;
use App\Order;
use App\OrderItem;
use App\ProductVariationOptions;
use App\Product;
use App\Transaction;
use Auth;
use Session;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class SecureCheckout extends Controller {


    public function __construct()
    {
        $request = new Request("get");
        $location = str_replace("/", "_", $request->location());

        $this->hasAuthAccess("auth", "/sign-in/rd/".$location);
        
    }


    public static function amount($cart) {

        $subtotal = 0;
        $total_items = 0;

        if($cart != null) {

            foreach($cart as $item) {

                $total_items = ($total_items + $item->quantity);
                if($item->variant != 0) 
                {
                    $itm_amount = ($item->variation()->price *  $item->quantity);
                    $subtotal = ($subtotal + $itm_amount);
                }
                else 
                {

                    if($item->product()->discount_price != '0.00') 
                    {
                        $itm_amount = ($item->product()->discount_price *  $item->quantity);
                    }
                    else 
                    {
                        $itm_amount = ($item->product()->price *  $item->quantity);
                    }

                    $subtotal = ($subtotal + $itm_amount);
                }

            }

            return $subtotal;
        }

        return null;

    }

    public static function create_new_address($customer, $request, $is_default = true) {

        
        $request->required([
            "firstname" => "string",
            "lastname" => "string",
            "street" => "string",
            "city" => "string",
            "state" => "string",
            "phone" => "string" 
        ]);

        if($request->validation == true) {

            $address = [
                "customer" => $customer,
                "firstname" => $request->firstname,
                "lastname" => $request->lastname,
                "street" => $request->street,
                "additional_address" => $request->suite,
                "city" => $request->city,
                "state" => $request->state,
                "zip" => $request->zip,
                "phone" => $request->phone,
                "is_default" => ($is_default) ? 1 : 0
            ];
    
            $create = (new AddressBook)->insert($address);
    
            if($create) {
    
                return $create;
            }

        } 

        

        return false;

    }

    public static function process_order_items($order, $cart, $customer) {

        foreach ($cart as $item) {
            # code...

            $price = $item->product()->price;
            if($item->variant != 0) {
                $price = $item->variation()->price;
            }

            $o_item = [
                "vendor" => $item->product()->creator()->id,
                "order" => $order->id,
                "product" => $item->product,
                "quantity" => $item->quantity,
                "price" => $price,
                "variant" => $item->variant
            ];

            if((new OrderItem)->insert($o_item)) {

                if($item->variant != 0) {
                    $new_quantity = ($item->variation()->quantity - $item->quantity);
                    (new ProductVariationOptions)->where("id", $item->variant)->update(["quantity" => $new_quantity]);
                }
                else {
                    $new_quantity = ($item->product()->quantity - $item->quantity);
                    (new Product)->where("id", $item->product)->update(["quantity" => $new_quantity]);
                }

            }

        }

        (new Cart)->clear($customer);

    }

    public function index(Request $request) {
        
        $data["title"] = 'Checkout';
        $data["header"] = 'Checkout';

        $customer = Session::get("auth");
        $cart = (new Cart)->where("user", $customer)->all();
        if($cart == null) { return redirect("/cart"); }

        if($request->method == "POST") {

            $address_is_sorted = false;
            $address = 0;

            if(isset($request->address)) {

                if($request->address == "different") {

                    $new_address = static::create_new_address($customer, $request, false);
                    if($new_address) {
                        $address = $new_address->id;
                        $address_is_sorted = true;
                    }

                }
                else { 
                    $address = $request->address; 
                    $address_is_sorted = true;
                }

            }
            
            else {

                $new_address = static::create_new_address($customer, $request);
                if($new_address) {
                    $address = $new_address->id;
                    $address_is_sorted = true;
                }

            }



            if($address_is_sorted) {

                // Create Order
                $order_info = [
                    "customer" => $customer,
                    "address" => $address,
                    "amount" => static::amount($cart),
                    "shipping_fee" => 0,
                    "payment_method" => $request->pay_method,
                    "payment_status" => 316,
                    "status" => 1,
                ];

                $order = (new Order)->insert($order_info);
                if($order) {
                    // Process Order Items
                    static::process_order_items($order, $cart, $customer);
                    Session::set("order_tran_id", $order->id);

                    if($request->pay_method == "Card") {
                        
                        return redirect("/order/payment");

                    }
                    else if($request->pay_method == "Paypal") {


                    }

                }

            }

        }


        $addresses = (new AddressBook)->where("customer", $customer)->orderBy("id", "ASC", 2)->all();

        $data["cart"] = $cart;
        $data["addresses"] = $addresses;
        return view("checkout/index", $data);
    }


    public function card_payment(Request $request) {

        $data["title"] = 'Order Payment';
        $data["header"] = 'Make Payment';

        if(Session::get("order_tran_id")) {

            $order = Session::get("order_tran_id");

            if($request->method == "POST") {

                if(isset($request->gateway)) {

                    $transaction = new Transaction;

                    if($request->gateway == "Flutterwave") {

                        if($transaction->flutterwave($order)) {
                            Session::set("t_reference", $transaction->reference);
                            return redirectToHost($transaction->location);
                        }
                    }
                    else if($request->gateway == "Paystack") {

                        if($transaction->paystack($order)) {
                            Session::set("t_reference", $transaction->reference);
                            return redirectToHost($transaction->location);
                        }
                    }

                }

            }



            return view("checkout/card-options", $data);
        }

        return error404();
    } 

    public function flutterwave(Request $request) {

        if(Session::get("t_reference") && Session::get("order_tran_id")) {

            $reference = Session::get("t_reference");
            $order = Session::get("order_tran_id");

            $transaction =  new Transaction;
            if($transaction->flutterwave_verification($reference)) {

                $data = ["status" => 111];
                $transaction->where("reference", $reference)->update($data);
                (new Order)->where("id", $order)->update(["payment_status" => 111]);

                Session::end("t_reference");
                Session::end("order_tran_id");

                return redirect("/uac/order/success/".$order);

            }
            else {

                $data = ["status" => 901];
                $transaction->where("reference", $reference)->update($data);
                (new Order)->where("id", $order)->update(["payment_status" => 901]);

                return view("checkout/card-payment-failed");
            }

        }

        return error404();
    }

    public function paystack(Request $request) {

        if(Session::get("t_reference") && Session::get("order_tran_id")) {
            
            $reference = Session::get("t_reference");
            $order = Session::get("order_tran_id");

            $transaction =  new Transaction;
            if($transaction->paystack_verification($reference)) {

                $data = ["status" => 111];
                $transaction->where("reference", $reference)->update($data);
                (new Order)->where("id", $order)->update(["payment_status" => 111]);

                Session::end("t_reference");
                Session::end("order_tran_id");

                return redirect("/uac/order/success/".$order);

            } 
            else {

                $data = ["status" => 901];
                $transaction->where("reference", $reference)->update($data);
                (new Order)->where("id", $order)->update(["payment_status" => 901]);

                return view("checkout/card-payment-failed");
            }
        }

        return error404();
    }

}