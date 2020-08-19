<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;
use App\Admin\Auth;
use App\Variation;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class Api_Variation extends Controller {

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

    public function get_variations() {

        $variations = (new Variation)->orderBy("name", "ASC")->all();
        $list = array();

        foreach($variations as $variation) {
            $data = array("name" => $variation->name, "value" => $variation->id);
            array_push($list, $data);
        }

        return Json($list);
    }

    public function get_variations_table() {

        $variations = (new Variation)->orderBy("id", "ASC")->all();
        $list = array();

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

        return Json($list);
    }    

}