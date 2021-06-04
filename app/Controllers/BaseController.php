<?php

namespace App\Action\Urls\Controllers;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

use App\Core\Urls\Request;
use App\Notification\TestNotification;


class BaseController extends Controller 
{

    public function index() {

        return view("index");
    }

}