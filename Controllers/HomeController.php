<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class HomeController extends Controller {

    public function __construct()
    {
        //$this->hasAuthAccess("user", "login");
    }

    public function index()
    {
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
    }

}