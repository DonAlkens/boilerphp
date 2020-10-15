<?php

namespace App\Action\Urls\Controllers\Vendor;


use App\Action\Urls\Controllers\Controller;
use App\Collection;
use App\Core\Urls\Request;
use App\Hashing\Hash;
use App\User;
use App\VendorAddress;
use App\VendorDetail;
use Session;
use Auth;
use App\Role;
use App\Admin\Door;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class AuthController extends Controller {

    public function __construct() {
        
        if(Session::get("auth")) {
            $location = "/";
            return redirect($location);
        }

    }

    public function login(Request $request) {

        $data["hide_cart"] = true;
        $data["v_reg"] = true;
        $data["title"] = "Login";
        // $data["header"] = "Login";

        if($request->method == "POST") {

            $request->required([
                "email" => "email",
                "password" => "string"
            ]);

            if($request->validation == true) {

                $vendor = (new User)->where(["email" => $request->email, "is_vendor" => 1])->get();
                if($vendor) {

                    if($vendor->blocked) {
                        $data["message"] = "Your account has been blocked. Kindly contact us more information.";
                    }
                    else 
                    {
                        if(Hash::verify($request->password, $vendor->password)) {
                            if($vendor->verified) {

                                $role = (new Role)->where("id", $vendor->role)->get();
                                $permissions = $role->permissions();

                                if($permissions != null) {
                                    $locks = array();

                                    foreach($permissions as $permission) {

                                        array_push($locks, strtolower($permission->permission()->name));
                                    }

                                    Door::createLocks($locks);
                                }

                                Auth::login($vendor);
                                return redirect("/dashboard");
                            } 
                            else {
                                // Send new verification notification
                                Session::set("vendor", $vendor->id);
                                return redirect("verfication");
                            }
                        }
                        else {
                            $data["message"] = "Incorrect email or password!";
                        }
                    }
                }
                else {
                    $data["message"] = "Account not found. <br>Please check the details and try again!";
                }

            }

        }

        return view("vendor/auth/login", $data);

    }
    
    public function register(Request $request) {

        $data["hide_cart"] = true;
        $data["v_login"] = true;
        $data["title"] = "Start Selling Today";
        // $data["header"] = "Register";


        if($request->method == "POST") {

            $request->required([
                "firstname" => "string",
                "lastname" => "string",
                "email" => "email",
                "phone" => "string",

                "store_name" => "string",
                "street" => "string",
                "city" => "string",
                "state" => "string",
                
                "own_brand" => "integer",
                "category" => "integer",
                "means_of_known" => "string",
                "password" => "string",
                "confirm_password" => "string"
            ]);


            if($request->validation == true) {

                $data = [
                    "firstname" => $request->firstname, 
                    "lastname" => $request->lastname,
                    "email" => $request->email,
                    "password" => Hash::create($request->password),
                    "is_vendor" => 1,
                    "role" => 2
                ];
                $user = (new User)->insert($data);
                if($user) {

                    $details = [
                        "user" => $user->id,
                        "vendor_name" => $request->store_name,
                        "phone" => $request->phone,
                        "website" => $request->website,
                        "own_brand" => $request->own_brand,
                        "product_category" => $request->category,
                        "means_of_knowing" => $request->means_of_known,
                        "referral_id" => date("si").rand(100,999),
                        "referred_by" => $request->referral,
                    ];  

                    $vendor_details = (new VendorDetail)->insert($details);
                    if($vendor_details) {

                        $address = [
                            "user" => $user->id,
                            "vendor" => $vendor_details->id,
                            "street" => $request->street,
                            "additional_address" => $request->suite,
                            "city" => $request->city,
                            "state" => $request->state,
                            "country" => "Nigeria",
                            "zip" => $request->zip
                        ];

                        $vendor_address = (new VendorAddress)->insert($address);
                        if($vendor_address) {

                            // Init Wallet
                            (new VendorDetail)->insert(["vendor" => $user->id, "balance" => 0, "active" => 1]);
                            

                            Session::set("vendor", $user->id);
                            return redirect("/verification");
                        }

                    }

                }

                $data["failed"] = true;

            }
        }


        $collections = (new Collection)->all();
        $data["collections"] = $collections;
        
        return view("vendor/auth/register", $data);
    }

    public function verification() {

        if(Session::get("vendor")) {

            $data["hide_cart"] = true;
            $data["title"] = "Verify Account";
            $data["header"] = "Verify Account";

            $id = Session::get("vendor");
            $user = (new User)->where("id", $id)->get();
            $data["vendor"] = $user;

            return view("vendor/auth/verification", $data);
        }

        return error404();

    }

    public function logout() 
    {
        Session::end("auth");
        return redirect("login");
    }

}