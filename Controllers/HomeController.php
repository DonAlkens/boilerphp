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
<<<<<<< HEAD
        return view("index");
    }

    public function category()
    {
        return view("category");
    }

    public function cart()
    {
        return view("cart");
    }

    public function checkout()
    {
        return view("checkout");
    }

    public function contact()
    {
        return view("contact");
    }

    public function details()
    {
        return view("details");
=======
        $locks = array("users", "members");
        Door::createLocks($locks);

        return view("home");
>>>>>>> 1ccbbde990cfc5eb525efb03da22118148580de4
    }

}