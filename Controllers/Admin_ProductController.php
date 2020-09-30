<?php

namespace App\Action\Urls\Controllers;


use App\Core\Urls\Request;
use App\Admin\Door;
use App\Product;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class Admin_ProductController extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth","signin");

        (new Door)->openWith("manage products", function(){
            return content("<b>Access denied: You do not have permission to acces this page.<b>");
        });
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

    public function create_variation_list_forms(Request $request) 
    {
        return view("/admin/product/variations_list_form", 
        ["variations" => json_decode($request->variations), "price" => $request->price]);
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