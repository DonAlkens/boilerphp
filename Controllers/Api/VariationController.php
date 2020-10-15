<?php

namespace App\Action\Urls\Controllers\Api;


use App\Action\Urls\Controllers\Controller;
use App\Admin\Door;
use App\Core\Urls\Request;
use Auth;
use App\Product;
use App\Variation;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class VariationController extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth", "/signin");

        (new Door)->openWith("manage variations", function(){

            $response = array(
                "status" => 200,
                "success" => false,
                "error" => array(
                    "message" => "Access denied: You do not have permission to manage variations"
                )
            );
            return Json($response);
        });
    }

    public function add(Request $request) {


        $error_message = "Please add a least on variation.";

        if(!empty($request->variations[0])) {
            
            $existing = [];

            foreach($request->variations as $variation) {
                $check = (new Variation)->where("name", $variation)->get();
                if($check){
                    array_push($existing, $variation);
                } 
                else {
                    $create = (new Variation)->insert(["name" => $variation, "created_by" => Auth::user()->id]);
                }
            }

            return Json(["status" => 200, "success" => true, "message" => "New variation(s) has been successfully added."]);

        } 
        else 
        {
            $error_message = "First variation(s) item must not be empty. Fill or remove field.";
        } 

        return Json(["status" => 200, "success" => false, "error" => ["message" => $error_message]]);
        
    }

    public function edit_variation(Request $request) {

        $variation = (new Variation)->where("id", $request->id)->get();
        if($variation) {
            if($request->variation != $variation->name) {

                $check = (new Variation)->where("name", $request->variation)->get();
                if($check) {

                    $message = "Variation $request->variation already exists.";
                    return Json(["status" => 200, "success" => false, "error"=>["message" => $message]]);
                }
                else {

                    $update = $variation->where("id", $request->id)->update([
                        "name" => $request->variation
                    ]);

                    $variation = (new Variation)->where("id", $variation->id)->get();

                    if($update) {

                        $message = "Variation $request->id has been updated successfully.";
                        $creator = $variation->creator()->email;
                        $updator = $variation->updator()->email;

                        $data = array(
                            $variation->id,
                            $variation->name,
                            $creator,
                            $variation->created_date,
                            // $updator,
                            // $variation->last_updated_date
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

    public function delete_variation(Request $request) {

        $check = (new Variation)->where("id", $request->id)->get();

        if($check) {
            
            if($check->delete("id", $check->id)){
                $message = "Variation $check->name has been deleted successfully.";
                return Json(["status" => 200, "success" => true, "message" => $message]);
            }

            $message = "Unable to delete this variation!";
            return Json(["status" => 200, "success" => false, "error"=>["message" => $message]]);
        }

        $message = "Error occured. This variation does not exists!";
        return Json(["status" => 200, "success" => false, "error"=>["message" => $message]]);
    }

    public function get_variations() {

        $variations = (new Variation)->orderBy("name", "ASC")->all();
        $list = array();
        
        if($variations != null){

            foreach($variations as $variation) {
                $data = array("name" => $variation->name, "value" => $variation->id);
                array_push($list, $data);
            }

        }

        return Json($list);
    }

    public function get_variations_table() {

        $variations = (new Variation)->orderBy("id", "ASC")->all();
        $list = array();

        if($variations != null){
            foreach($variations as $variation) {
    
                $data = array(
                    $variation->id, 
                    $variation->name, 
                    $variation->creator()->email, 
                    $variation->created_date, 
                    // $variation->updator()->email, 
                    // $variation->last_updated_date
                );
    
                array_push($list, $data);
    
            }
        }


        return Json($list);
    }    

    public function get_variation_details(Request $request) {

        $variation = (new Variation)->where("id", $request->param["id"])->get();
        if($variation) {

            $creator = $variation->creator()->firstname." ".$variation->creator()->lastname." (".$variation->creator()->email.")";
            $updator = "";
            if($variation->last_updated_by != null) {
                $updator = $variation->updator()->firstname." ".$variation->updator()->lastname." (".$variation->updator()->email.")";
            }

            $details = array(
                "id" => $variation->id,
                "name" => $variation->name,
                "creator" => $creator,
                "created_date" => $variation->created_date,
                "updator" => $updator,
                "updated_date" => $variation->last_updated_date
            );

            return Json($details);
        }

        return null;
    }

}