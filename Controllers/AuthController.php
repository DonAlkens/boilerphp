<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;
use App\Admin\Auth;
use App\Customer;
use App\Cart;
use App\Hashing\Hash;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class AuthController extends Controller {


    public static function auth_check(Request $request) {
        
        if(Session::get("auth")) {

            $location = "/uac/dashboard";

            if(isset($request->param["location"])) {
                $url = $request->param["location"];
                $location = str_replace("_", "/", $url);
            }

            return redirect($location);;
        }

    }

    
    public function sign_in(Request $request) {
        
        $data["title"] = "Sign In";
        if(isset($request->param["location"])) { $data["location"] = $request->param["location"]; }


        if($request->method == "POST") {

            if(isset($request->email) && isset($request->password)) {

                if((new Customer)->exists($request->email)) {

                    $customer = (new Customer)->where("email", $request->email)->get();

                    if(Hash::verify($request->password, $customer->password)) {

                        Session::set("auth", $customer->id);

                        if(Session::get("cart")) {
                            $cart = Session::get("cart");
                            (new Cart)->propagate($customer->id, $cart);
                        }

                    }
                    else {
                        $data["error_message"] = "Incorrect email or password!";
                    }

                }
                else {

                    $data["error_message"] = "Account not found!. <a href='/sign-up'>Click to create account</a>";
                }

            }

        }


        static::auth_check($request);
        return view("auth/sign-in", $data);
    }

    public function sign_up(Request $request) {
        
        $data["title"] = "Sign up";
        if(isset($request->param["location"])) { $data["location"] = $request->param["location"]; }


        if($request->method == "POST") {


            if(isset($request->email) && isset($request->name) && isset($request->password)) {

                if((new Customer)->exists($request->email)) {

                    $data["error_message"] = "Email address already exists!";
                }

                else {

                    $password = Hash::create($request->password);
                    $customer = (new Customer)->new($request->name, $request->email, $password, 1);

                    if($customer) {

                        Session::set("auth", $customer->id);

                        if(Session::get("cart")) {
                            $cart = Session::get("cart");
                            (new Cart)->propagate($customer->id, $cart);
                        }

                    }

                    else {

                        $data["error_message"] = "Unable to create account. Please try again!";
                    }
                }

            }

        }


        static::auth_check($request);
        return view("auth/sign-up", $data);
    }


    public function logout() {

        Session::end("auth");
        return redirect("sign-in");

    }


}