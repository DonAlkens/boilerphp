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

    public function index(Request $request) {

        echo $request->param["india"];
        return view("index");
    }

    public function home(Request $request) {

        echo $request->_subdomain;
        return view("index");
    }

}