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

}