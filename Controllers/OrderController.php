<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class OrderController extends Controller {

    public function index() {
        

        return view("order/index");
    }

}