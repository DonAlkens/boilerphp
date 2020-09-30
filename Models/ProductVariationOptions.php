<?php

namespace App;

use App\Core\Database\Model;


class ProductVariationOptions extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public $table = "product_variation_options";

    
    public function delete_images($product) {

        $options = $this->where("product", $product)->all();

        if($options) {

            foreach($options as $option) {
                
                if(strpos($option->images, ",")) { $images = explode(",", $option->images); } 
                else { $images = array($option->images); }

                foreach($images as $image) {
                    $file = "src/images/".$image; unlink($file);
                }

            }

        }

    }


}

?>