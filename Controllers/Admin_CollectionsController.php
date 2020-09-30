<?php

namespace App\Action\Urls\Controllers;


use App\Core\Urls\Request;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class Admin_CollectionsController extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth", "signin");
    }

    public function index()
    {
        return view("admin/collection/collections");
    }

    public function add_form()
    {
        return view("admin/collection/add_form");
    }

    public function categories() 
    {
        return view("admin/collection/categories/categories");
    }

    public function add_category_form()
    {
        return view("admin/collection/categories/add_form");
    }

}