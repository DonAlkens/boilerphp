<?php

namespace App\Action\Urls\Controllers\Admin;


use App\Action\Urls\Controllers\Controller;
use App\Activity;
use App\Admin\Door;
use App\Core\Urls\Request;
use App\Withdrawal;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class TransactionController extends Controller {

    public function __construct() {

        $this->hasAuthAccess("auth", "signin");

        (new Door)->openWith("manage transactions", function(){
            return content("Access Denied!. You have not being granted permission.");
        });
        
    }

    public function transactions() {
        
        return view("admin/transaction/payments");
    }

    public function new_withdrawals(Request $request) {
        
        $data["title"] = "New Withdrawals";
        $data["heading"] = "New Withdrawals";
        $data["group"] = "new";

        if($request->method == "POST") {
            if(isset($request->processed)) {
    
                $withdrawal = (new Withdrawal)->where("id", $request->processed)->get();
                if($withdrawal && $withdrawal->status == "0") {
                    $withdrawal->where("id", $withdrawal->id)->update(["status" => 1]);
    
                    $data["success"] = true;
    
                    (new Activity)->log(
                        ["user" => auth()->id, 
                        "description" => auth()->email. " proccedd withrawal with ID: $withdrawal->id."
                    ]);
                    
                }
    
            }
        }


        return view("admin/transaction/withdrawals", $data);
    }

    public function processed_withdrawals() {
        
        $data["title"] = "Processed Withdrawals";
        $data["heading"] = "Processed Withdrawals";
        $data["group"] = "processed";

        return view("admin/transaction/withdrawals", $data);
    }

}