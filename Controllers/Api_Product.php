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

    public function add(Request $request) {

        $request->required([
            "category" => "integer",
            "name" => "string",
            "price" => "string",
            "condition" => "string",
            "brand" => "string",
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
                    "discount" => $request->discount,
                    "brand" => $request->brand,
                    "collection" => (new Category)->where("id", $request->category)->get()->collection,
                    "category" => $request->category,
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
                        if(!empty($variation) && !empty($request->variation_value[$index]) 
                        && !empty($request->variation_quantity[$index])) {

                            $v_available = true;

                            $price = !empty($request->variation_price[$index]) ? $request->variation_price[$index] : $created->price;

                            $data = array(
                                "product" => $created->id, 
                                "variation" => $variation,  
                                "value" => $request->variation_value[$index],
                                "price" => $price,
                                "quantity" => $request->variation_quantity[$index],
                                "created_by" => Auth::user()->id
                            );

                            $create_variation = (new ProductVariation)->insert($data);
                            if($create_variation) { $v = true; }

                        }

                        $index++;
                    }


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
            return Json(["status" => 200, "success" => false, "message" => $message]);
        }
    }


    public function get_prouducts_table() {

        $products = (new Product)->orderBy("id", "asc")->all();
        $list = array();

        foreach($products as $product) {

            $image = '<img src="/src/images/'. $product->images()->main .'" alt="'. $product->name .'">';
            $category = $product->collection()->name."/".$product->category()->name;
            $price = "&dollar;".$product->price;

            $data = array(
                $product->id, 
                $image,
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

}