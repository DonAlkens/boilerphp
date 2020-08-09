<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class Admin_DashboardController extends Controller {

    public function __construct()
    {
        //$this->hasAuthAccess("user", "login");
    }

    public function index()
    {
        return view("admin/index");
    }

}