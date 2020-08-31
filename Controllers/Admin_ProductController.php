<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;
use App\Product;

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

    public function catalogue() 
    {
        return view("admin/product/catalogue");
    }

    public function add_form()
    {
        return view("admin/product/add_form");
    }

    public function edit(Request $request)
    {
        $product = (new Product)->where("id", $request->param["id"])->get();
        if($product != null) {
            return view("admin/product/edit_form", ["product" => $product]);
        } 
        else {
            return redirect("/a/products");
        }
    }

}