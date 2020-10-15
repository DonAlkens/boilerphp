<?php

namespace App\Action\Urls\Controllers\Vendor;


use App\Action\Urls\Controllers\Controller;
use App\Core\Urls\Request;
use App\VendorWallet;
use App\Withdrawal;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class WithdrawalController extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth","login");
    }

    public function index(Request $request) {

        $data["title"] = "My Withdrawals";
        
        if($request->method == "POST") {

            $request->required([
                "account_name" => "string",
                "account_number" => "==:10",
                "bank_name" => "string"
            ]);

            if($request->validation == true) {
                
                $wallet = (new VendorWallet)->where("vendor", auth()->id)->get();
    
                $amount = str_replace(",", "", $request->amount);
                $amount = (float) $amount;
                
    
                if($amount > $wallet->balance) {

                    $data["error"] = true;
                    $data["message"] = "Insuffient balance.";

                } 
                else {
                    
                    $withdraw = [
                        "vendor" => auth()->id,
                        "account_name" => $request->account_name,
                        "account_number" => $request->account_number,
                        "bank" => $request->bank_name,
                        "amount" => $request->amount
                    ];

                    #withdraw 
                    if((new Withdrawal)->insert($withdraw)) {

                        $balance = (float) ($wallet->balance - $amount);
                        $wallet->where("vendor", auth()->id)->update(["balance" => $balance]);

                        $data["success"] = true;
                        $data["message"] = "Withdrawal has been submitted succesfully and will be processed in less than 48hrs."; 

                    }
    
                }

            }

        }

        return view("vendor/withdrawal/index", $data);
    }


    public function withdraw(Request $request) {

        return view("vendor/withdrawal/withdraw");
    }

}