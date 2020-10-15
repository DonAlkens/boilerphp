<?php

namespace App\Action\Urls\Controllers\Admin;


use App\Action\Urls\Controllers\Controller;
use App\Core\Urls\Request;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class DashboardController extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth", "signin");
    }

    public function index()
    {
        return view("admin/index");
    }

}