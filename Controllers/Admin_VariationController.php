<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class Admin_VariationController extends Controller {

    public function __construct() {

        $this->hasAuthAccess("auth", "signin");
        
    }

    public function index() {

        return view("admin/variation/variations");

    }

    public function add_form() {

        return view("admin/variation/add_form");

    }

}