<?php

namespace App\Action\Urls\Controllers\Admin;

use App\Action\Urls\Controllers\Controller;
use App\Admin\Door;
use App\Core\Urls\Request;
use App\User;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class VendorController extends Controller {


    public function __construct() {

        $this->hasAuthAccess("auth", "signin");

        (new Door)->openWith("manage vendors", function(){
            return content("Access Denied!. You have not being granted permission.");
        });
        
    }


    public function approved() {
        
        $data["group"] = "approved";
        $data["heading"] = "Approved Vendors";

        return view("admin/vendor/vendors", $data);
    }

    public function pending() {
        
        $data["group"] = "pending";
        $data["heading"] = "Pending Vendors";

        return view("admin/vendor/vendors", $data);
    }

    public function blocked() {
        
        $data["group"] = "blocked";
        $data["heading"] = "Blocked Vendors";

        return view("admin/vendor/vendors", $data);
    }

    public function view(Request $request) {

        $data["title"] = "Vendor Profile";

        $vendor = (new User)->where("id", $request->param["id"])->get();
        if($vendor) {

            if(isset($request->activate)) {
                if($vendor->approved == "0") {

                    $vendor->where("id", $vendor->id)->update(["approved" => 1]);
                    $vendor = (new User)->where("id", $vendor->id)->get();

                    $data["approved"] = true;
                }
            }

            else if(isset($request->de_activate)) {
                if($vendor->approved == "1") {

                    $vendor->where("id", $vendor->id)->update(["approved" => 0]);
                    $vendor = (new User)->where("id", $vendor->id)->get();

                    $data["disapproved"] = true;
                }
            }

            if(isset($request->block)) {
                if($vendor->blocked == "0") {

                    $vendor->where("id", $vendor->id)->update(["blocked" => 1]);
                    $vendor = (new User)->where("id", $vendor->id)->get();

                    $data["blocked"] = true;
                }
            }

            else if(isset($request->unblock)) {
                if($vendor->blocked == "1") {

                    $vendor->where("id", $vendor->id)->update(["blocked" => 0]);
                    $vendor = (new User)->where("id", $vendor->id)->get();

                    $data["unblocked"] = true;
                }
            }

            
            $data["vendor"] = $vendor;

            return view("admin/vendor/details", $data);
        }

        return error404();

    }

}