<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class Admin_ProductController extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth","signin");
    }

    public function index()
    {
        return view("admin/product/index"); # Call Miss for your babe
    }

    public function add_form()
    {
        return view("admin/product/add_form");
    }

}