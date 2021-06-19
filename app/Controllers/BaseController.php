<?php

namespace App\Action\Urls\Controllers;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

use App\Core\Urls\Request;
use App\User;

class BaseController extends Controller 
{

    public function index() {

        $user = (new User)->all();

        return view("index");
    }

}