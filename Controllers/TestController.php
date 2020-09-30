<?php

namespace App\Action\Urls\Controllers;


use App\Core\Urls\Request;
use Auth;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class TestController extends Controller {

    public function index()
    {
        Auth::login(1);
        return view("test/index");
    }

}