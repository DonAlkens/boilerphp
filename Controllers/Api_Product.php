<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;
use App\Admin\Auth;
use App\Category;
use App\FileSystem\Fs;
use App\Product;
use App\ProductImage;
use App\ProductSettings;
use App\ProductVariation;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class Api_Product extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth", "/signin");
    }

    public function add(Request $request) {

        $request->required([
            "collection" => "integer",
            "category" => "integer",
            "name" => "string",
            "price" => "string",
            "condition" => "string",
            "brand" => "string",
            "color" => "string",
            "description" => "string",
            "search_keywords" => "string"
        ]);

        if($request->validation == true) {

            $p = false; $m = false; $v = false; $v_available = false; $s = false;

            $product = (new Product);

            $check = $product->where([
                "name" => $request->name,
                "slug" => $product->create_slug($request->name),
                "created_by" => Auth::user()->id
                ])->get();

            if($check) {

                $message = "You've created a similar product in the past, You can edit instead, Click to edit product <a href='/a/products/edit/". $check->id ."'>Edit Product </a>";
                return Json(["status" => 200, "success" => false, "error" => ["message" => $message]]);

            }

            else {

                # creating new Product
                $created = $product->insert([
                    "name" => $request->name,
                    "slug" => $product->create_slug($request->name),
                    "description" => $request->description,
                    "price" => $request->price,
                    "quantity" => $request->quantity,
                    "discount" => $request->discount,
                    "discount_price" => $request->discount_price,
                    "brand" => $request->brand,
                    "color" => $request->color,
                    "collection" => $request->collection,
                    "category" => isset($request->category) ? $request->category : 0,
                    "sub_category" => isset($request->sub_category) ? $request->sub_category : 0,
                    "created_by" => Auth::user()->id
                ]);

                if($created) {  $p = true; // product created successfully;
                    
                    $properties = array(
                        "filename"=> "image", 
                        "path" => "src/images/", 
                        "rename" => "pd-".$created->id."-main-image-".date("Ymdhis")
                    );
                    

                    // Product Images
                    if(Fs::uploadImage($properties)) {

                        $image = array(
                            "product" => $created->id, 
                            "main" =>Fs::get_filename()
                        );

                        $product_image = (new ProductImage)->insert($image);

                        if($product_image){ $m = true; // Product Main Image Created Successfully

                            if(!empty($request->gallery)) {
                                
                                $properties = array(
                                    "filename"=> "gallery", 
                                    "path" => "src/images/", 
                                    "prefix" => "pd-".$created->id
                                );

                                if(Fs::uploadMultipleImage($properties)) {
                                    $images = implode(",", Fs::$filelist);
                                    $product_image->where("id", $product_image->id)->update(["gallery" => $images]);
                                    
                                }

                            }
                        }


                    }

                    // Product Variations
                    $index = 0;
                    foreach($request->variations as $variation) {
                        if(!empty($variation) && !empty($request->variation_options[$index])) {

                            $v_available = true;

                            $options = $request->variation_options[$index];
                            if(strpos($options, ",")) {
                                $options = explode(",", $options);
                            }
                            else 
                            {
                                $options = array($options);
                            }

                            foreach($options as $option) {

                                $data = array(
                                    "product" => $created->id, 
                                    "variation" => $variation,  
                                    "name" => $option
                                );
    
                                $check = (new ProductVariation)->where($data)->get();
    
                                if(!$check) {
    
                                    $data["created_by"] = Auth::user()->id;
    
                                    $create_variation = (new ProductVariation)->insert($data);
                                    if($create_variation) { $v = true; }
    
                                }

                            }

                        }
                        $index++;
                    }

                    // Product Variation Options
                    


                    // Product Setting 
                    $featured = $out_of_stock = 0;
                    if(isset($request->featured)) { $featured = 1; } 
                    if(isset($request->out_of_stock)) { $out_of_stock = 1; }

                    $settings = array(
                        "product" => $created->id,
                        "search_keywords" => $request->search_keywords,
                        "featured" => $featured,
                        "out_of_stock" => $out_of_stock,
                        "created_by" => Auth::user()->id
                    );

                    if((new ProductSettings)->insert($settings)) {
                        $s = true;
                    }

                }


                if($p == true && $m == true && $s == true) 
                {
                    $message = "Product has been saved successfully.";
                    $response = array("status" => 200, "success" => true, "message" => $message);

                    if($v_available == true && $v == false) 
                    {
                        $message = "Error occurred while saving. Please try again!";
                        $response = array("status" => 200, "success" => false, "error" => ["message" => $message]);

                        (new Product)->delete_images($created->id);
                        (new Product)->delete("id", $created->id);
                    }
                }
                else 
                {
                    $message = "Unable to save product. Please try again!";
                    $response = array("status" => 200, "success" => false, "error" => ["message" => $message]);

                    (new Product)->delete_images($created->id);
                    (new Product)->delete("id", $created->id);

                }

                return Json($response);

            }

        }

        else {
            $message = "Some required fields are not filled correctly. Please check and try again.";
            return Json(["status" => 200, "success" => false, "error" => ["message" => $message]]);
        }
    }

    public function get_products()
    {
        $products = (new Product)->orderBy("id", "desc")->all();
        $list = array();

        foreach($products as $product) {

            $product->price = str_replace(",", "", $product->price);
            $product->price = "&#8358;".number_format((int)$product->price, 2);

            $data = array(
                "id" => $product->id,
                "name" => $product->name,
                "brand" => $product->brand,
                "color" => $product->color,
                "price" => $product->price,
                "quantity" => $product->quantity,
                "discount" => $product->discount,
                "discount_price" => $product->discount_price,
                "description" => $product->description,
                "collection" => $product->collection()->name,
                "category" => ($product->category()) ? $product->category()->name : "",
                "sub_category" => ($product->sub_category()) ? $product->sub_category()->name : "",
                "image" => $product->images()->main,
                "gallery" => !empty($product->images()->gallery) 
                            ? explode(",", $product->images()->gallery) : [],
                "settings" => array(
                    "keywords" => $product->settings()->search_keywords,
                    "out_of_stock" => $product->settings()->out_of_stock,
                    "featured" => $product->settings()->featured
                ),
                "created_by" => $product->creator()->email,
                "created_date" => $product->created_date,
                "updated_by" => $product->updator()->email, 
                "updated_date" => $product->last_updated_date
            );

            array_push($list, $data);
        }

        return Json($list);
    }

    public function get_prouducts_table() {

        $products = (new Product)->orderBy("id", "asc")->all();
        $list = array();

        foreach($products as $product) {

            $product->name = '<img src="/src/images/'. $product->images()->main .'" alt="'. $product->name .'"> '.$product->name;
            $category = $product->collection()->name;
            if($product->category()) {
                $category .= "/".$product->category()->name;
            }
            $price = "&dollar;".$product->price;

            $data = array(
                $product->id,
                $product->name,
                $category,
                $price
                // $category->updator()->email, 
                // $category->last_updated_date
            );

            array_push($list, $data);
        }

        return Json($list);

    }

    public function edit_product_information(Request $request) {

        $request->required([
            "collection" => "integer",
            "category" => "integer",
            "name" => "string",
            "price" => "string",
            "quantity" => "integer",
            "condition" => "string",
            "brand" => "string",
            "color" => "string",
            "description" => "string",
        ]);

        if($request->validation == true) {

            $product = (new Product)->where("id", $request->param["id"])->get();

            if($product) {

                $slug = (new Product)->create_slug($request->name);

                $data = array(
                    "name" => $request->name,
                    "slug" => $slug,
                    "brand" => $request->brand,
                    "color" => $request->color,
                    "collection" => $request->collection,
                    "category" => isset($request->category) ? $request->category : 0,
                    "sub_category" => isset($request->sub_category) ? $request->sub_category : 0,
                    "price" => $request->price,
                    "quantity" => $request->quantity,
                    "discount" => $request->discount,
                    "discount_price" => $request->discount_price,
                    "description" => $request->description,
                    "last_updated_date" => $request->timestamp(),
                    "last_updated_by" => Auth::user()->id
                );

                if($product->where("id", $request->param["id"])->update($data)) {

                    $message = "Changes has been saved successfully.";
                    $response = array("status" => 200, "success" => true, "message" => $message);

                } 
                else {
                    $message = "Unable to save changes. Please try again";
                    $response = array("status" => 200, "success" => false, "error" => ["message" => $message]);
                } 

            } 
            else {
                $message = "Product not found, this product might have been deleted. Please refresh and try again";
                $response = ["status" => 200, "success" => false, "error" => ["message" => $message]];
            }

        }
        else {
            $message = "Some required fields are not filled correctly. Please check and try again.";
            $response = ["status" => 200, "success" => false, "error" => ["message" => $message]];
        }

        return Json($response);

    }

    public function edit_product_images(Request $request) {

        $id = $request->param["id"];
        $images = (new ProductImage)->where("product", $id)->get(); 

        $m = $m_a = $g = $g_a = false;

        if(!is_null($request->file("image"))) { $m_a = true;

            $file = "src/images/".$images->main;
            if(file_exists($file)) { unlink($file); }

            $rename = "pd-".$id."-main-image-".date("Ymdhis");

            $properties = array(
                "filename"=> "image", 
                "path" => "src/images/", 
                "rename" => $rename
            );

            if(Fs::uploadImage($properties)) { $m = true; 
                $name = Fs::get_filename();
                $images->where("product", $id)->update([
                    "main" => $name,
                    "last_updated_date" => $request->timestamp(),
                    "last_updated_by" => Auth::user()->id
                ]);
            }
        }

        if(!is_null($request->file("gallery"))) { $g_a = true;

            $properties = array(
                "filename"=> "gallery", 
                "path" => "src/images/", 
                "prefix" => "pd-".$id
            );

            if(Fs::uploadMultipleImage($properties)) {  $g = true;

                // echo $images->gallery; return;
                
                $images->gallery .= ",". implode(",", Fs::$filelist);

                $images->where("product", $id)->update([
                    "gallery" => $images->gallery,
                    "last_updated_date" => $request->timestamp(),
                    "last_updated_by" => Auth::user()->id
                ]);
                
            }
        }

        if(($m_a && $m) || ($g_a && $g)) {

            $message = "Change has been updated successfully.";
            $response = array("status" => 200, "success" => true, "message" => $message);

        }  else {

            $message = "Unable to save changes. Please try again";
            $response = array("status" => 200, "success" => false, "error" => ["message" => $message]);
        }

        return Json($response);


    }

    public function remove_images(Request $request) {

        $id = $request->param["id"];
        $images = (new ProductImage)->where("product", $id)->get();
        $gallery = explode(",", $images->gallery);

        if(unlink($request->image_location)) {

            array_splice($gallery, $request->image_id, 1);
            $gallery = implode(",", $gallery);

            $data = array(
                "gallery" => $gallery,
                "last_updated_date" => $request->timestamp(),
                "last_updated_by" => Auth::user()->id
            );

            if($images->where("product", $id)->update($data)) {

                $message = "Image has been removed successfully.";
                $response = array("status" => 200, "success" => true, "message" => $message);

                return Json($response);
            } 

        }

        $message = "Unable to remove image. Please try again";
        $response = array("status" => 200, "success" => false, "error" => ["message" => $message]);

        return Json($response);

    }

    public function edit_product_variations(Request $request) {

         // Product Variations
         $id = $request->param["id"];
         $product = (new Product)->where("id", $id)->get();

         
        if($product) {

            $v_available = $v = $p = $p_available = false;

            if(isset($request->pd_var))
            {
                $variations = (new ProductVariation)->where("product", $id)->all();

                if($variations && is_array($variations)) {

                    foreach($variations as $variation) {

                        if(isset($request->pd_var[$variation->id])) { $p_available = true;

                            $data = array(
                                "variation" => $request->pd_var[$variation->id],
                                "value" => $request->pd_var_value[$variation->id],
                                "price" => $request->pd_var_price[$variation->id],
                                "quantity" => $request->pd_var_quantity[$variation->id],
                                "last_updated_date" => $request->timestamp(),
                                "last_updated_by" => Auth::user()->id
                            );

                            if($variation->where("id", $variation->id)->update($data)) {
                                $p = true;
                            }

                        }
                    }

                }
            }

            if(isset($request->variations)) {

                $index = 0;
                foreach($request->variations as $variation) {

                    if(!empty($variation) && !empty($request->variation_value[$index]) 
                    && !empty($request->variation_quantity[$index])) {
        
                        $v_available = true;
        
                        $price = !empty($request->variation_price[$index]) 
                                ? $request->variation_price[$index] 
                                : $product->price;
                        
                        $value = $request->variation_value[$index];
        
                        $data = array(
                            "product" => $product->id, 
                            "variation" => $variation,  
                            "value" => $value
                        );

                        $check = (new ProductVariation)->where($data)->get();

                        if(!$check) {

                            $data["price"] = $price;
                            $data["quantity"] = $request->variation_quantity[$index];
                            $data["created_by"] = Auth::user()->id;

                            $create_variation = (new ProductVariation)->insert($data);
                            if($create_variation) { $v = true; }

                        }
        
                    }
        
                    $index++;
                }

            }

            if($v_available && !$v) {
                
                $message = "Unable to create new variation(s). Please try again";
                $response = array("status" => 200, "success" => false, "error" => ["message" => $message]);

                return Json($response);
            } 
            else if($p_available && !$p) {

                $message = "Unable to save changes. Please try again";
                $response = array("status" => 200, "success" => false, "error" => ["message" => $message]);

                return Json($response);

            }
            else  {

                $message = "Changes has been saved successfully.";
                $response = array("status" => 200, "success" => true, "message" => $message);

                return Json($response);
            }
        }

        $message = "Error Occured. Product does not exists.";
        $response = array("status" => 200, "success" => false, "error" => ["message" => $message]);

        return Json($response);

    }

    public function remove_variations(Request $request) {

        if(isset($request->param["id"])) {
            
            $id = $request->param["id"];
            $variation = (new ProductVariation)->where("id", $id)->get();

            if($variation) {

                if($variation->where("id", $id)->delete())
                {
                    $message = "Variation has been removed successfully.";
                    $response = array("status" => 200, "success" => true, "message" => $message);

                    return Json($response);
                }
                else 
                {
                    $message = "Unable to remove variation. Please try again";
                    $response = array("status" => 200, "success" => false, "error" => ["message" => $message]);

                    return Json($response);
                }
            }
            else 
            {
                $message = "Error Occured, variation does not exists.";
                $response = array("status" => 200, "success" => false, "error" => ["message" => $message]);

                return Json($response);
            }
            
            
        }

    }

    public function edit_product_settings(Request $request) {

        $request->required([
            "search_keywords" => "string"
        ]);

        if($request->validation == true)
        {
            // Product Setting 
            $product = (new Product)->where("id", $request->param["id"])->select("id, name");
    
            if($product) {
    
                $featured = $out_of_stock = 0;
                if(isset($request->featured)) { $featured = 1; } 
                if(isset($request->out_of_stock)) { $out_of_stock = 1; }
        
                $settings = array(
                    "product" => $product->id,
                    "featured" => $featured,
                    "search_keywords" => $request->search_keywords,
                    "out_of_stock" => $out_of_stock,
                    "last_updated_date" => $request->timestamp(),
                    "last_updated_by" => Auth::user()->id
                );
        
                if((new ProductSettings)->where("product", $product->id)->update($settings)) {
                    
                    $message = "Settings has been saved successfully.";
                    $response = array("status" => 200, "success" => true, "message" => $message);
    
                    return Json($response);
                }
                else  
                {
    
                    $message = "Unable to save settings. Please try again";
                    $response = array("status" => 200, "success" => false, "error" => ["message" => $message]);
    
                    return Json($response);
    
                }
            }
    
            $message = "Error Occured. Product does not exists.";
            $response = array("status" => 200, "success" => false, "error" => ["message" => $message]);

        }
        else 
        {
            $message = "Some required fields are not filled correctly. Please check and try again.";
            $response = array("status" => 200, "success" => false, "error" => ["message" => $message]);
        }

        return Json($response);

    }

}