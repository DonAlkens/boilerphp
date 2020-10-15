<?php

namespace App\Action\Urls\Controllers\Api;


use App\Action\Urls\Controllers\Controller;
use App\Admin\Door;
use App\Core\Urls\Request;
use App\Customer;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class CustomerController extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth", "/signin");

        (new Door)->openWith("manage customers", function(){

            $response = array(
                "status" => 200,
                "success" => false,
                "error" => array(
                    "message" => "Access denied: You do not have permission to manage customers."
                )
            );
            return Json($response);
        });
    }


    public function customers() {

        $customers = (new Customer)->orderBy("id", "DESC")->all();
        $list = array();

        if($customers) {

            foreach($customers as $customer) {

                $data = [
                    $customer->id,
                    $customer->firstname,
                    $customer->lastname,
                    $customer->email,
                    $customer->created_date,
                    $customer->sign_up_method
                ];

                array_push($list, $data);

            }

        }

        return Json($list);
    }

}