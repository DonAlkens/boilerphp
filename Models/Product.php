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