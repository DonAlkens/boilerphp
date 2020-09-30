<?php

namespace App\Action\Urls\Controllers;


use App\Core\Urls\Request;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class OrderController extends Controller {

    public function index() {
        

        return view("order/index");
    }

}