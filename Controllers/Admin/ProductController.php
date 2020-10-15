<?php

namespace App\Action\Urls\Controllers\Admin;


use App\Action\Urls\Controllers\Controller;
use App\Core\Urls\Request;
use App\Admin\Door;
use App\Product;
use Auth;
use App\Activity;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class ProductController extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth","signin");

        (new Door)->openWith("manage products", function(){
            return content("<b>Access denied: You do not have permission to acces this page.<b>");
        });
    }

    public function index()
    {
        return view("admin/product/index");
    }

    public function catalogue() 
    {
        return view("admin/product/catalogue");
    }

    public function pending()
    {
        return view("admin/product/index", ["pending" => true]);
    }

    public function hidden()
    {
        return view("admin/product/index", ["hidden" => true]);
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

    public function view(Request $request) {

        if(isset($request->param["id"])) {

            $id = $request->param["id"];
            $product = (new Product)->where("id", $id)->get();

            if($product) {

                return view("admin/product/details", ["product" => $product]);
            }

        }

        return error404();
    }

    public function hide(Request $request) {

        $product = (new Product)->where("id", $request->param["id"])->get();
        if($product) {

            $data = [
                "hide" => 1,
                "last_updated_date" => $request->timestamp(),
                "last_updated_by" => Auth::user()->id,
            ];

            if((new Product)->where("id", $product->id)->update($data)) {

                (new Activity)->log(
                    ["user" => Auth::user()->id, 
                    "is_product" => $product->id, 
                    "description" => Auth::user()->email. " hide this product."
                ]);

                return view("admin/product/hide", ["success" => true]);

            }

            return view("admin/product/hide", ["success" => false]);

        }

        return error404();

    }
}