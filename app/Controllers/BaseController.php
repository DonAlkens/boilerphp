<?php

namespace App\Action\Urls\Controllers;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

use App\Core\Urls\Request;


class BaseController extends Controller 
{

    public function index() {

        return view("index");
    }

}