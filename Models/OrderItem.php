<?php

namespace App;

use App\Core\Database\Model;


class OrderItem extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public $table = "order_items";

    public function product() {

        return $this->hasOne(Product::class, ["id" => "product"]);
    }

    public function order() {

        return $this->hasOne(Order::class, ["id" => "order"]);
    }

    public function variation() {

        return $this->hasOne(ProductVariationOptions::class, ["id" => "variant"]);
    }

    public function get_item_props() {

        if($this->variant) {
            $get_varition = (new ProductVariationOptions)->where("id", $this->variant)->get();
            if($get_varition) {
                
                $image = null;

                $image_holder = $get_varition->where("id", $get_varition->image_holder)->get();
                if($image_holder) {
                    $image = $image_holder->images;

                    if(strpos($image_holder->images, ",")){
                        $image = explode(",", $image_holder->images)[0];
                    }
                }

                $variations = array($get_varition->variant);

                if(strpos($get_varition->variant, "/")) {
                    $variations = explode("/", $get_varition->variant);
                }


                $var_list = array();

                foreach($variations as $variation) {

                    $get_props = (new ProductVariation)->where(["product" => $this->product, "name" => $variation])->get();
                    
                    if($get_props) {
                        $index_data = array("name" => $get_props->name);

                        $prop_name = strtolower($get_props->variation()->name);
                        if($prop_name == "colors"  || $prop_name == "color") {
                            $index_data["type"] = "Item Color";
                        }
                        else if($prop_name == "sizes"  || $prop_name == "size") {
                            $index_data["type"] = "Size";
                            
                        }

                        array_push($var_list, $index_data);
                    }
                }

                return ["image" => $image, "variant" => $var_list];
            }
        }

    }

}

?>