<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;
use App\Admin\Auth;
use App\Category;
use App\Collection;
use App\SubCategory;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class Api_Collection extends Controller {

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
                    $slug = str_replace([" ", "'", ","], "-", $collection);
                    $slug = strtolower($slug);
                    $create = (new Collection)->insert(["name" => $collection, "slug" => $slug, "created_by" => Auth::user()->id]);
                }
            }

            $message = "New collection(s) has been successfully added.";
            (count($existing) > 0) 
            ? $message .= "\nSome already existing collection(s) where not added.".implode(",", $existing) 
            : null;

            return Json(["status" => 200, "success" => true, "message" => $message]);

        } 
        else 
        {
            $error_message = "First collection(s) item must not be empty. Fill or remove field.";
        } 

        return Json(["status" => 200, "success" => false, "error" => ["message" => $error_message]]);
        
    }


    public function get_collections() {

        $collections = (new Collection)->orderBy("name", "ASC")->all();
        $list = array();

        foreach($collections as $collection) {
            $data = array("name" => $collection->name, "value" => $collection->id);
            array_push($list, $data);
        }

        return Json($list);

    }

    public function get_collections_table() {

        $collections = (new Collection)->orderBy("id", "DESC")->all();
        $list = array();

        foreach($collections as $collection) {

            $updator = isset($collection->updator()->email) ? $collection->updator()->email : "";

            $data = array(
                $collection->id, 
                $collection->name, 
                $collection->creator()->email, 
                $collection->created_date, 
                $updator, 
                $collection->last_updated_date
            );

            array_push($list, $data);

        }

        return Json($list);
    }


    public function add_categories(Request $request) {

        $request->required([
            "collection" => "integer",
            "name" => "string"
        ]);

        if($request->validation == true) {

            $check = (new Category)->where("name", $request->name)->get();
            if($check) {

                return Json(["status" => 200, "success" => false, "error"=>["message" => "Category name already exists."]]);
            }

            $slug = str_replace([" ", "'", ","], "-", $request->name);
            $slug = strtolower($slug);

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
                            $slug = str_replace([" ", "'", ","], "-", $sub_cat);
                            $slug = strtolower($slug);
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

}