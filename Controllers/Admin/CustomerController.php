<?php

namespace App\Action\Urls\Controllers\Admin;


use App\Action\Urls\Controllers\Controller;
use App\Admin\Door;
use App\Core\Urls\Request;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class CustomerController extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth", "/signin");

        (new Door)->openWith("manage customers", function(){

            return content("Access Denied!. You have not being granted permission.");
        });
    }

    public function index() {
        
        return view("admin/customer/customers");
    }

}