<?php

namespace App\Action\Urls\Controllers\Admin;


use App\Action\Urls\Controllers\Controller;
use App\Admin\Door;
use App\Core\Urls\Request;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class CollectionsController extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth", "signin");

        (new Door)->openWith("manage collections", function(){
            return content("Access Denied!. You have not being granted permission.");
        });
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