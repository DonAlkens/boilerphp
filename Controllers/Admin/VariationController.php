<?php

namespace App\Action\Urls\Controllers\Admin;


use App\Action\Urls\Controllers\Controller;
use App\Admin\Door;
use App\Core\Urls\Request;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class VariationController extends Controller {

    public function __construct() {

        $this->hasAuthAccess("auth", "signin");

        (new Door)->openWith("manage variations", function(){
            return content("Access Denied!. You have not being granted permission.");
        });
        
    }

    public function index() {

        return view("admin/variation/variations");

    }

    public function add_form() {

        return view("admin/variation/add_form");

    }

}