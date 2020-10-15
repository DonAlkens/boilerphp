<?php

namespace App\Action\Urls\Controllers\Api;


use App\Action\Urls\Controllers\Controller;
use App\Admin\Door;
use App\Core\Urls\Request;
use Auth;
use App\Category;
use App\Collection;
use App\Product;
use App\SubCategory;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class CollectionController extends Controller {

    public function __construct()
    {
        // $this->hasAuthAccess("auth", "/signin");

        // (new Door)->openWith("manage collections", function(){

        //     $response = array(
        //         "status" => 200,
        //         "success" => false,
        //         "error" => array(
        //             "message" => "Access denied: You do not have permission to manage collections"
        //         )
        //     );
        //     return Json($response);
        // });
    }

    public function add(Request $request) {


        $error_message = "Please add a least on collection.";

        if(!empty($request->collections[0])) {
            
            $existing = [];

            foreach($request->collections as $collection) {
                $check = (new Collection)->where("name", $collection)->get();
                if($check){
                    array_push($existing, $collection);
                } 
                else {
                    $slug = (new Product)->create_slug(($collection));
                    $create = (new Collection)->insert(["name" => $collection, "slug" => $slug, "created_by" => Auth::user()->id]);
                }
            }

            $message = "New collection(s) has been successfully added.";
            (count($existing) > 0) 
            ? $message .= "\n Some already existing collection(s) where not added.".implode(",", $existing) 
            : null;

            return Json(["status" => 200, "success" => true, "message" => $message]);

        } 
        else 
        {
            $error_message = "First collection(s) item must not be empty. Fill or remove field.";
        } 

        return Json(["status" => 200, "success" => false, "error" => ["message" => $error_message]]);
        
    }

    public function edit_collection(Request $request) {

        $collection = (new Collection)->where("id", $request->id)->get();
        if($collection) {
            if($request->collection != $collection->name) {

                $check = (new Collection)->where("name", $request->collection)->get();
                if($check) {

                    $message = "Collection $request->collection already exists.";
                    return Json(["status" => 200, "success" => false, "error"=>["message" => $message]]);
                }
                else {

                    $update = $collection->where("id", $request->id)->update([
                        "name" => $request->collection,
                        "slug" => (new Product)->create_slug($request->collection)
                    ]);

                    $collection = (new Collection)->where("id", $collection->id)->get();

                    if($update) {

                        $message = "Collection $request->id has been updated successfully.";
                        $creator = $collection->creator()->email;
                        $updator = $collection->updator()->email;

                        $data = array(
                            $collection->id,
                            $collection->name,
                            $creator,
                            $collection->created_date,
                            // $updator,
                            // $collection->last_updated_date
                        );

                        return Json(["status" => 200, "success" => true, "message" => $message, "data" => $data]);

                    }

                    $message = "Unable to save changes, Please try again.";
                    return Json(["status" => 200, "success" => false, "error"=>["message" => $message]]);

                }
            }
        }

        return Json(array());
    }

    public function delete_collection(Request $request) {

        $check = (new Collection)->where("id", $request->id)->get();

        if($check) {
            
            if($check->delete("id", $check->id)){
                $message = "Collection $check->name has been deleted successfully.";
                return Json(["status" => 200, "success" => true, "message" => $message]);
            }

            $message = "Unable to delete this collection!";
            return Json(["status" => 200, "success" => false, "error"=>["message" => $message]]);
        }

        $message = "Error occured. This collection does not exists!";
        return Json(["status" => 200, "success" => false, "error"=>["message" => $message]]);
    }

    public function get_collections() {

        $collections = (new Collection)->orderBy("name", "ASC")->all();
        $list = array();

        if($collections != null){
            foreach($collections as $collection) {
                $data = array("name" => $collection->name, "value" => $collection->id);
                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function get_collection_details(Request $request) {

        $collection = (new Collection)->where("id", $request->param["id"])->get();
        if($collection) {

            $creator = $collection->creator()->firstname." ".$collection->creator()->lastname." (".$collection->creator()->email.")";
            
            $updator = "";
            if($collection->last_updated_by != null) {
                $updator = $collection->updator()->firstname." ".$collection->updator()->lastname." (".$collection->updator()->email.")";
            }

            $details = array(
                "id" => $collection->id,
                "name" => $collection->name,
                "creator" => $creator,
                "created_date" => $collection->created_date,
                "updator" => $updator,
                "updated_date" => $collection->last_updated_date
            );

            return Json($details);
        }

        return null;
    }

    public function get_collections_table() {

        $collections = (new Collection)->orderBy("id", "ASC")->all();
        $list = array();

        if($collections != null){
            foreach($collections as $collection) {

                $data = array(
                    $collection->id, 
                    $collection->name, 
                    $collection->creator()->email, 
                    $collection->created_date, 
                    // $collection->updator()->email, 
                    // $collection->last_updated_date
                );

                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function add_categories(Request $request) {

        $request->required([
            "collection" => "integer",
            "name" => "string"
        ]);

        if($request->validation == true) {

            $check = (new Category)->where(["name" => $request->name, "collection" => $request->collection])->get();
            if($check) {

                return Json(["status" => 200, "success" => false, "error"=>["message" => "Category name already exists."]]);
            }

            $slug = (new Product)->create_slug(($request->name));

            $category = (new Category)->insert([
                "name" => $request->name, 
                "slug" => $slug,
                "collection" => $request->collection, 
                "created_by" => Auth::user()->id
            ]);

            if($category) {

                $existing = [];

                if(!empty($request->subCategories[0])) {
                    foreach($request->subCategories as $sub_cat) {

                        $check = (new SubCategory)->where("name", $sub_cat)->get();

                        if($check){

                            array_push($existing, $sub_cat);
                        } 
                        else {
                            $slug = (new Product)->create_slug(($sub_cat));
                            $create = (new SubCategory)->insert(["name" => $sub_cat, "slug" => $slug, "category" => $category->id, "created_by" => Auth::user()->id]);
                        }

                    }
                }

                $message = "Category has been successfully added.";
                (count($existing) > 0) 
                ? $message .= "\nSome already existing sub-categories(s) where not added.".implode(",", $existing) 
                : null;

                return Json(["status" => 200, "success" => true, "message" => $message]);
            }

        }

    }

    public function edit_category(Request $request) {

        $category = (new Category)->where("id", $request->id)->get();

        if($category) {

            $check = (new Category)
                    ->where(["name" => $request->category, "collection" => $request->collection])
                    ->get();

            if($check) {

                $message = "Category $request->category already exists.";
                return Json(["status" => 200, "success" => false, "error"=>["message" => $message]]);
            }
            else {

                $update = $category->where("id", $request->id)->update([
                    "name" => $request->category,
                    "collection" => $request->collection,
                    "slug" => (new Product)->create_slug($request->category)
                ]);

                $category = (new Category)->where("id", $category->id)->get();

                if($update) {

                    $message = "Category $request->id has been updated successfully.";
                    $creator = $category->creator()->email;
                    $updator = $category->updator()->email;

                    $data = array(
                        $category->id,
                        $category->name,
                        $category->collection()->name,
                        $creator,
                        $category->created_date,
                        // $updator,
                        // $category->last_updated_date
                    );

                    return Json(["status" => 200, "success" => true, "message" => $message, "data" => $data]);

                }

                $message = "Unable to save changes, Please try again.";
                return Json(["status" => 200, "success" => false, "error"=>["message" => $message]]);

            }
            
        }

        return Json(array());
    }

    public function delete_category(Request $request) {

        $check = (new Category)->where("id", $request->id)->get();

        if($check) {
            
            if($check->delete("id", $check->id)){

                $message = "Category $check->name has been deleted successfully.";
                return Json(["status" => 200, "success" => true, "message" => $message]);
            }

            $message = "Unable to delete this collection!";
            return Json(["status" => 200, "success" => false, "error"=>["message" => $message]]);
        }

        $message = "Error occured. This collection does not exists!";
        return Json(["status" => 200, "success" => false, "error"=>["message" => $message]]);
    }

    public function get_categories() {

        $categories = (new Category)->orderBy("name", "ASC")->all();
        $list = array();

        if($categories != null){
            foreach($categories as $category) {

                $data = array("name" => $category->name, "value" => $category->id);
                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function get_category_details(Request $request) {

        $category = (new Category)->where("id", $request->param["id"])->get();
        if($category) {

            $creator = $category->creator()->firstname." ".$category->creator()->lastname." (".$category->creator()->email.")";
            
            $updator = "";
            if($category->last_updated_by != null) {
                $updator = $category->updator()->firstname." ".$category->updator()->lastname." (".$category->updator()->email.")";
            }

            $details = array(
                "id" => $category->id,
                "name" => $category->name,
                "collection" => $category->collection()->name,
                "creator" => $creator,
                "created_date" => $category->created_date,
                "updator" => $updator,
                "updated_date" => $category->last_updated_date
            );

            return Json($details);
        }

        return null;
    }

    public function get_collection_categories(Request $request) {

        $categories = (new Category)->where("collection", $request->param["collection"])->orderBy("name", "ASC")->get();
         
        $list = array();
        
        if($categories != null){
            if(!is_array($categories)) { $categories = array($categories);}

            foreach($categories as $category) {
                
                $data = array("name" => $category->name, "value" => $category->id);
                array_push($list, $data);
            }
        }

        return Json($list);
    }

    public function get_categories_table() {

        $categories = (new Category)->orderBy("id", "ASC")->all();
        $list = array();

        if($categories != null){
            foreach($categories as $category) {
    
                $data = array(
                    $category->id, 
                    $category->name, 
                    $category->collection()->name,
                    $category->creator()->email, 
                    $category->created_date, 
                    // $category->updator()->email, 
                    // $category->last_updated_date
                );
    
                array_push($list, $data);
    
            }
        }


        return Json($list);
    }    

    public function subcategories(Request $request) {

        $subcategories = (new SubCategory)->orderBy("name", "ASC")->get();
        $list = array();

        if(gettype($subcategories) == "array") {

            foreach($subcategories as $subcategory) {

                $data = array(
                    "name" => $subcategory->name, 
                    "value" => $subcategory->id
                );
                
                array_push($list, $data);
            }
    
            return Json($list);
        }

        return Json($list);
    }

    public function get_subcategories(Request $request) {

        $subcategories = (new SubCategory)->where("category", $request->param["category"])->orderBy("name", "ASC")->get();
        $list = array();


        if(gettype($subcategories) == "array") {

            foreach($subcategories as $subcategory) {

                $data = array(
                    "name" => $subcategory->name, 
                    "value" => $subcategory->id
                );
                
                array_push($list, $data);
            }
    
            return Json($list);
        }

        return Json($list);
    }

}