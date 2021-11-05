<?php

namespace App\Action\Urls\Controllers\Api;


use App\Action\Urls\Controllers\Controller;
use App\Core\Urls\Request;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class ContactController extends Controller {

    public function index() {
        
        return view("contact/index");
    }

}