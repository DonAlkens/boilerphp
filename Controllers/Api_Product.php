<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;
use App\Admin\Auth;
use App\Category;
use App\FileSystem\Fs;
use App\Product;
use App\ProductImage;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class Api_Product extends Controller {

    public function add(Request $request) {

        $request->required([
            "category" => "integer",
            "name" => "string",
            "price" => "string",
            "condition" => "string",
            "brand" => "string",
            "description" => "string",
            "image" => "string",
            "search_keywords" => "string"
        ]);

        if($request->validation == true) {

            $p = false; $m = false; $v = false; $s = false;

            $product = (new Product);

            $check = $product->where([
                "name" => $request->name,
                "slug" => $product->create_slug($request->name),
                "created_by" => Auth::user()->id
                ])->get();

            if($check) {

                $message = "You've created a similar product in the past, You can edit instead, Click to edit product <a href='/a/products/edit/". $check->id ."'>Edit Product </a>";
                return Json(["status" => 200, "success" => false, "message" => $message]);

            }

            else {

                # creating new Product
                $create = $product->insert([
                    "name" => $request->name,
                    "slug" => $product->create_slug($request->name),
                    "description" => $request->description,
                    "price" => $request->price,
                    "brand" => $request->brand,
                    "collection" => (new Category)->where("id", $request->category)->collection,
                    "category" => $request->category,
                    "sub_category" => $request->sub_category,
                    "created_by" => Auth::user()->id
                ]);

                if($create) {  $p = true; // product created successfully;
                    
                    $properties = array(
                        "filename"=> "image", 
                        "path" => "src/images/", 
                        "rename" => "pd-".$create->id."-main-image-".date("Ymdhis")
                    );
                    

                    // Product Images
                    if(Fs::uploadImage($properties)) {

                        $image = array(
                            "product" => $create->id, 
                            "main" =>Fs::get_filename()
                        );

                        $product_image = (new ProductImage)->insert($image);

                        if($product_image){ $m = true; // Product Main Image Created Successfully

                            if(!empty($request->gallery)) {
                                
                                $properties = array(
                                    "filename"=> "gallery", 
                                    "path" => "src/images/", 
                                    "prefix" => "pd-".$create->id
                                );

                                if(Fs::uploadMultipleImage($properties)) {
                                    $images = implode(",", Fs::$filelist);
                                    $product_image->where("id", $product_image->id)->update(["gallery" => $images]);
                                    
                                }

                            }
                        }


                    }

                    // Product Variations
                    if(count($request->variations) > 0) {
                        
                    }

                }

            }

        }

        else {
            $message = "Some required fields are not filled correctly. Please check and try again.";
            return Json(["status" => 200, "success" => false, "message" => $message]);
        }
    }

}