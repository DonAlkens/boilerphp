<?php

namespace App;

use App\Core\Database\Model;


class Product extends Model {

    /**
    * defining all required fields
    **/
    protected $required = [];


    public function creator() {

        return $this->hasOne(User::class, ["id" => "created_by"]);

    }

    public function updator() {

        return $this->hasOne(User::class, ["id" => "last_updated_by"]);

    }

    public function images() {

        return $this->hasOne(ProductImage::class, ["product" => "id"]);

    }

    public function collection() {

        return $this->hasOne(Collection::class, ["id" => "collection"]);

    }

    public function category() {

        return $this->hasOne(Category::class, ["id" => "category"]);

    }

    public function sub_category() {

        return $this->hasOne(SubCategory::class, ["id" => "sub_category"]);
        
    }

    public function settings() {
        
        return $this->hasOne(ProductSettings::class, ["product" => "id"]);
    }

    public function reviews() {

        return $this->hasMultiple(ProductReview::class, ["product" => "id"]);
    }

    public function variations() {

        return (new ProductVariation)
                ->where("product", $this->id)
                ->groupBy("variation")
                ->orderBy("id")
                ->all();
    }

    public function price_range() {

        $this->use_price = $this->price;
        if($this->discount_price > 0) { $this->use_price = $this->discount_price; }

        $basic_price = $lowest = $highest = $this->use_price;

        if($this->options()) {
            $options = $this->options();
            foreach($options as $option) {
                if($option->price > $highest) {
                    $highest = $option->price;
                } 
                else if($option->price < $lowest) {
                    $lowest = $option->price;
                }
            }


            if($lowest != $this->use_price || $highest != $this->use_price) {
                $prices = array(
                    "lowest" => $lowest,
                    "highest" => $highest,
                );

                return $prices;
            }


        }

        return null;

    }

    public function variation_colors() {

        if($this->variations() != null) {

            $data = array();

            $variations = $this->variations();
            $var_id = 0;
            foreach($variations as $variation) {
                if(strtolower($variation->variation()->name) == "colors" 
                || strtolower($variation->variation()->name) == "color") {
                    $var_id = $variation->variation;
                    break;
                }
            }

            if($var_id > 0) {
                $colors = (new ProductVariation)
                            ->where(["product" => $this->id, "variation" => $var_id])
                            ->all();

                foreach($colors as $color) {
                    
                    // Check for Images
                    $color->name = trim($color->name);
                    $check = (new ProductVariationOptions)
                            ->query("SELECT * FROM product_variation_options WHERE product = '$this->id' AND variant LIKE '%".$color->name."%'");
                    $check = $this->resultFormatter($check->fetchAll(), true);

                    $index = array("id" => $color->id, "color" => $color->name, "image" => null);
                    foreach($check as $opt) {
                        
                        if($opt->images != null) {
                            $first_image = explode(",", $opt->images)[0];

                            $index["image"] = $first_image;      
                        }

                    }

                    array_push($data, $index);
                }
            }

            return $data;

        }

        return null;
    }

    public function variation_sizes() {

        if($this->variations() != null) {

            $data = array();

            $variations = $this->variations();
            $var_id = 0;
            foreach($variations as $variation) {
                if(strtolower($variation->variation()->name) == "sizes" 
                || strtolower($variation->variation()->name) == "size") {
                    $var_id = $variation->variation;
                    break;
                }
            }


            if($var_id > 0) {
                $sizes = (new ProductVariation)
                            ->where(["product" => $this->id, "variation" => $var_id])
                            ->all();

                foreach($sizes as $size) {

                    $index = array("id" => $size->id, "size" => $size->name);
                    array_push($data, $index);

                }
            }

            

            return $data;
        }

        return null;
    }

    public function options() {

        return $this->hasMultiple(ProductVariationOptions::class, ["product" => "id"]);
    }

    // Utils functions

    public function create_slug($string) {

        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($string)); // Removes special chars.
    }


    public function delete_images($id) {

        $target = $this->where("id", $id)->get();

        $main = "src/images/".$target->images()->main;
        unlink($main);

        $gallery = $target->images()->gallery;
        if(!empty($gallery)) {

            $gallery_list = explode(",", $gallery);
            foreach($gallery_list as $image) {

                $file = "src/images/".$image;
                unlink($file);
            }

        }

    }

}

?>