<?php

namespace App;

use App\Core\Database\Model;


class VendorDetail extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];

    
    public $table = "vendor_details";


    public function category() {

        return $this->hasOne(Collection::class, ["id" => "product_category"]);
    }

    public function refferal() {

        if(isset($this->referred_by)) {
            $referral = $this->where("refferal_id", $this->referred_by);
            if($referral) {
                return (new User)->where("id", $referral->user)->get();
            }
        }

        return null;
    }

}

?>