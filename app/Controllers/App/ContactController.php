<?php

namespace App\Action\Urls\Controllers\App;


use App\Action\Urls\Controllers\Controller;
use App\Core\Urls\Request;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class ContactController extends Controller {

    public function index() {
        
        return view("app/contact/index");
    }

}