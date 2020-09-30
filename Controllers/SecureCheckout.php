<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;
use App\AddressBook;
use App\Cart;

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


    public function index(Request $request) {
        
        $data["title"] = 'Checkout';
        $data["header"] = 'Checkout';

        $customer = Session::get("auth");
        $cart = (new Cart)->where("user", $customer)->all();
        $address = (new AddressBook)->where("customer", $customer)->all();

        if($cart == null) {
            return redirect("/cart");
        }

        $data["addresses"] = $address;
        $data["cart"] = $cart;
        return view("checkout/index", $data);
    }

}