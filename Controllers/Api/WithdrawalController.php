<?php

namespace App\Action\Urls\Controllers\Api;


use App\Action\Urls\Controllers\Controller;
use App\Core\Urls\Request;
use App\Withdrawal;
use App\Admin\Door;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class WithdrawalController extends Controller {

    public function __construct()
    {
        $this->hasAuthAccess("auth", "/login");

        (new Door)->openWith("manage withdrawal", function(){

            $response = array(
                "status" => 200,
                "success" => false,
                "error" => array(
                    "message" => "Access denied: You do not have permission to manage withdrawals"
                )
            );
            return Json($response);
        });
    }


    public function get_new_withdrawals() {

        $withdrawals = (new Withdrawal)->where("status", "0")->orderBy("id", "ASC")->all();
        $list = array();

        if($withdrawals) {
            foreach($withdrawals as $withdraw) {

                $withdraw->amount = "&#8358;".number_format($withdraw->amount, 2);
                $status = "<span class='badge badge-warning'>Pending</span>";
                if($withdraw->status == "1") {
                    $status = "<span class='badge badge-success'>Paid</span>";
                }

                $data = [
                    $withdraw->id,
                    $withdraw->vendor()->email,
                    $withdraw->account_name,
                    $withdraw->account_number,
                    $withdraw->bank,
                    $withdraw->amount,
                    $status,
                    $withdraw->created_date
                ];

                array_push($list, $data);

            }
        }

        return Json($list);

    }


    public function get_paid_withdrawals() {

        $withdrawals = (new Withdrawal)->where("status", "1")->orderBy("id", "ASC")->all();
        $list = array();

        if($withdrawals) {
            foreach($withdrawals as $withdraw) {

                $withdraw->amount = "&#8358;".number_format($withdraw->amount, 2);
                $status = "<span class='badge badge-warning'>Pending</span>";
                if($withdraw->status == "1") {
                    $status = "<span class='badge badge-success'>Paid</span>";
                }

                $data = [
                    $withdraw->id,
                    $withdraw->vendor()->email,
                    $withdraw->account_name,
                    $withdraw->account_number,
                    $withdraw->bank,
                    $withdraw->amount,
                    $status,
                    $withdraw->created_date
                ];

                array_push($list, $data);

            }
        }

        return Json($list);

    }


    public function get_vendor_withdrawals() {
        
        $withdrawals = (new Withdrawal)->where("vendor", auth()->id)->orderBy("id", "DESC")->all();
        $list = array();

        if($withdrawals) {
            foreach($withdrawals as $withdraw) {

                $withdraw->amount = "&#8358;".number_format($withdraw->amount, 2);
                $status = "<span class='badge badge-warning'>Pending</span>";
                if($withdraw->status == "1") {
                    $status = "<span class='badge badge-success'>Paid</span>";
                }

                $data = [
                    $withdraw->id,
                    $withdraw->account_name,
                    $withdraw->account_number,
                    $withdraw->bank,
                    $withdraw->amount,
                    $status,
                    $withdraw->created_date
                ];

                array_push($list, $data);

            }
        }

        return Json($list);

    }


    public function get_withdrawal(Request $request) {

        if(isset($request->param["id"])) {

            $withdrawal = (new Withdrawal)->where("id", $request->param["id"])->get();

            if($withdrawal) {
                return Json(["id" => $withdrawal->id]);
            }

        }

        return null;
    }

}