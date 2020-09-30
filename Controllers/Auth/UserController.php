<?php

namespace App\Action\Urls\Controllers\Auth;


use App\Action\Urls\Controllers\Controller;
use App\Core\Urls\Request;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class UserController extends Controller {

    public function index() {
        

        return view("home");
    }

}