<?php

namespace App\Action\Urls\Controllers\Api;


use App\Action\Urls\Controllers\Controller;
use App\Admin\Door;
use App\Core\Urls\Request;
use App\Transaction;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class TransactionController extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth", "/signin");

        (new Door)->openWith("manage transactions", function(){

            $response = array(
                "status" => 200,
                "success" => false,
                "error" => array(
                    "message" => "Access denied: You do not have permission to manage transactions"
                )
            );
            return Json($response);
        });
    }

    public function logs() {
        
        $transactions = (new Transaction)->orderBy("id", "DESC")->all();
        $list = array();

        if($transactions) {

            foreach($transactions as $transaction) {

                if($transaction->status == 111) {
                    $status = "<span class='badge badge-success'>Successful</span>";
                }
                else if($transaction->status == 316) {
                    $status = "<span class='badge badge-warning'>Pending</span>";
                }
                else if($transaction->status == 901) {
                    $status = "<span class='badge badge-danger'>Failed</span>";
                }
                else if($transaction->status == 579) {
                    $status = "<span class='badge badge-danger'>Cancelled</span>";
                }

                $amount = "<span>&#8358;".$transaction->amount."</span>";

                $data = [
                    $transaction->id,
                    $transaction->reference,
                    $transaction->order,
                    $transaction->customer()->email,
                    $amount,
                    $status,
                    $transaction->payment_method,
                    $transaction->created_date
                ];

                array_push($list, $data);

            }

        }

        return Json($list);

        return view("transaction/index");
    }

}