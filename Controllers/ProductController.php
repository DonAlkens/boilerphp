<?php

namespace App\Action\Urls\Controllers;


use App\Core\Urls\Request;
use App\Admin\Auth;
use App\Cart;
use App\Product;
use App\ProductVariation;
use App\ProductVariationOptions;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class ProductController extends Controller {

    public function __construct()
    {
        
    }

    public function add_to_cart(Request $request) {

        $product = (new Product)->where("id", $request->product)->get();
        if($product) {

            $added = false;

            $item = array(
                "product" => $product->id,
                "quantity" => $request->quantity,
                "variant" => 0
            );

            if($request->color != null || $request->size != null) {

                $code_1 = $request->color;
                $code_2 = $request->size;
                if($request->size != "" & $request->color != "") { $code_1 .= "/".$request->size; }
                if($request->color != "" & $request->size != "") { $code_2 .= "/".$request->color; }

                $get_variation = (new ProductVariationOptions)->where(["product" => $product->id, "variant" => $code_1])->get();
                if(!$get_variation) {
                    $get_variation = (new ProductVariationOptions)->where(["product" => $product->id, "variant" => $code_2])->get();
                }

                if($get_variation) {
                    
                    if($request->quantity > $get_variation->quantity) {
                        $response = array(
                            "status" => 200, 
                            "success" => false, 
                            "error" => ["message" => "Quantity not available."] );
                        return Json($response);
                    }

                    $item["variant"] = $get_variation->id;

                }

            }

            else if($request->quantity > $product->quantity) {
                $response = array(
                    "status" => 200, 
                    "success" => false, 
                    "error" => ["message" => "Quantity not available."] );
                return Json($response);
            }


            // Add to cart
            if( Session::get("auth") ) {

                $item["user"] = Session::get("auth");
                $insert = (new Cart)->insert($item);
                if($insert) { 
                    $item = $insert->id;
                    $added = true; 
                }

            }
            else 
            {
                # using persistent session 
                if(Session::get("cart")) {
                    $cart = Session::get("cart");
                    $count = count($cart);

                    array_push($cart, $item);

                    $item = ($count + 1);
                    Session::set("cart", $cart);
                }
                else {

                    $cart = array($item);
                    $item = 1;
                    Session::set("cart", $cart);
                }

                $added = true;
            }
            

            if($added == true) {
                $response = array(
                    "status" => 200, 
                    "success" => true, 
                    "item" => $item,
                    "message" => "Item has been added cart");
                return Json($response);
            }

        }
    }

    public function remove_item(Request $request) {

        $removed = false;

        if( Session::get("auth") )  {

            $check = (new Cart)->where("id", $request->item)->get();
            if($check) {
                if( (new Cart)->delete($check->id) ) {
                    $removed = true;
                }

            }

        } 
        else {

            if( Session::get("cart") ) {
                
                $cart = Session::get("cart"); $index = ($request->item - 1);

                if( array_splice($cart, $index, 1) ) {

                    Session::set("cart", $cart);
                    $removed = true;
                }

            }

        }

        if($removed == true) {
            $response = array(
                "status" => 200, 
                "success" => true, 
                "message" => "Item has been removed from cart");
            return Json($response);
        }

    }

    public function cart_widget() {

        $count = (Session::get("cart")) ? count(Session::get("cart")) : 0;

        if( Session::get("auth") ) {

            $customer = Session::get("auth");
            $items = (new Cart)->where("user",  $customer)->all();
            
            if($items) {

                $count = count($items);

            }
        }

        return view("product/cart-widget", array("count" => $count));
    }

    public function cart_response(Request $request) {

        $data = array("item" => $request->param["item"]);
        return view("product/cart-success", $data);
    }

    public function remove_response() {
        return view("product/cart-removed");
    }

    public function get_carting_data(Request $request) {

        $product = (new Product)->where("id", $request->product)->get();
        if($product) {
            return view("product/carting-content", array("product" => $product));
        }

    }

    public function get_var_information(Request $request) {

        $product = (new Product)->where("id", $request->param["product"])->get();

        if($product){

            $code_1 = $request->color;
            $code_2 = $request->size;
            if($request->size != "" & $request->color != "") { $code_1 .= "/".$request->size;}
            if($request->color != "" & $request->size != "") { $code_2 .= "/".$request->color;}

            $variant = (new ProductVariationOptions)->where(["product" => $product->id, "variant" => $code_1])->get();
            if($variant == null) {
                $variant = (new ProductVariationOptions)->where(["product" => $product->id, "variant" => $code_2])->get();
            }

            if($variant) {

                return Json(array(
                    "variant" => $variant->variant,
                    "price" => "&#8358;".number_format($variant->price),
                    "quantity" => $variant->quantity,
                    "in_stock" => $variant->in_stock
                ));

            }

        }

        return null;
    }

    public function get_var_images(Request $request) {

        $product = (new Product)->where("id", $request->param["product"])->get();

        if($product){
            
            $images = array();
            $request->color = trim($request->color);
            $variation = (new ProductVariation)->where(["product" => $product->id, "name" => $request->color])->get();


            $check = (new ProductVariationOptions)
                    ->query("SELECT * FROM product_variation_options WHERE product = '$product->id' AND variant LIKE '%".$variation->name."%'");
            $check = (new ProductVariationOptions)->resultFormatter($check->fetchAll(), true);

            
            foreach($check as $opt) {
                
                if($opt->images != null) {
                    $images = explode(",", $opt->images);
                    break;
                }

            }

            return view("product/images-slides-components", array("images" => $images));
        }


    }

    public function increment(Request $request) {

        if( Session::get("auth") ) { 

            (new Cart)->where("id", $request->index)->update(["quantity" => $request->qty]);

        }

        else if(Session::get("cart")) {

            $index = ($request->index - 1);
            $cart = Session::get("cart");
            $cart[$index]["quantity"] = $request->qty;

            Session::set("cart", $cart);

        }
    }

}