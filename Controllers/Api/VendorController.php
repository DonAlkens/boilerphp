<?php

namespace App\Action\Urls\Controllers\Api;


use App\Action\Urls\Controllers\Controller;
use App\Admin\Door;
use App\Core\Urls\Request;
use App\User;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class VendorController extends Controller {


    public function __construct()
    {
        $this->hasAuthAccess("auth", "/signin");

        (new Door)->openWith("manage vendors", function(){

            $response = array(
                "status" => 200,
                "success" => false,
                "error" => array(
                    "message" => "Access denied: You do not have permission to manage vendors"
                )
            );
            return Json($response);
        });
    }

    public function get_approved_vendors_table() {

        $vendors = (new User)->where(["is_vendor" => "1", "approved" => "1", "is_admin" => ""])->orderBy("id", "ASC")->all();
        $list = array();

        if($vendors != null){
            foreach($vendors as $vendor) {

                if(auth()->id == $vendor->id) {
                    continue;
                }

                $balance = "&#8358;".number_format($vendor->wallet()->balance, 2);
    
                $data = array(
                    $vendor->id, 
                    $vendor->details()->vendor_name,
                    $vendor->firstname." ".$vendor->lastname, 
                    $vendor->email,
                    $vendor->details()->phone,
                    $balance,
                    $vendor->created_date, 
                    // $variation->updator()->email, 
                    // $variation->last_updated_date
                );
    
                array_push($list, $data);
    
            }
        }


        return Json($list);
    }  

    public function get_pending_vendors_table() {

        $vendors = (new User)->where(["is_vendor" => "1", "approved" => "0"])->orderBy("id", "ASC")->all();
        $list = array();

        if($vendors != null){
            foreach($vendors as $vendor) {

                if(auth()->id == $vendor->id) {
                    continue;
                }
    
                $data = array(
                    $vendor->id, 
                    $vendor->details()->vendor_name,
                    $vendor->firstname." ".$vendor->lastname, 
                    $vendor->email,
                    $vendor->details()->phone,
                    $vendor->created_date, 
                    // $variation->updator()->email, 
                    // $variation->last_updated_date
                );
    
                array_push($list, $data);
    
            }
        }


        return Json($list);
    }

    public function get_blocked_vendors_table() {

        $vendors = (new User)->where(["is_vendor" => "1", "blocked" => "1"])->orderBy("id", "ASC")->all();
        $list = array();

        if($vendors != null){
            foreach($vendors as $vendor) {

                if(auth()->id == $vendor->id) {
                    continue;
                }
    
                $data = array(
                    $vendor->id, 
                    $vendor->details()->vendor_name,
                    $vendor->firstname." ".$vendor->lastname, 
                    $vendor->email,
                    $vendor->details()->phone,
                    $vendor->created_date, 
                    // $variation->updator()->email, 
                    // $variation->last_updated_date
                );
    
                array_push($list, $data);
    
            }
        }


        return Json($list);
    }

}