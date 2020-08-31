<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;
use App\Admin\Door;
use App\FileSystem\Fs;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class HomeController extends Controller {

    public function __construct()
    {
        //$this->hasAuthAccess("user", "login");
    }

    public function index(Request $request)
    {
        $locks = array("users", "members");
        Door::createLocks($locks);

        return view("home");
    }

}