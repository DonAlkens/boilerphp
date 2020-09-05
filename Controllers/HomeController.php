<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;
use App\Admin\Door;
use App\Category;
use App\Collection;
use App\FileSystem\Fs;
use App\Product;
use App\SubCategory;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class HomeController extends Controller {

    public static $data;

    public function __construct()
    {
        //$this->hasAuthAccess("user", "login");

        $this->data();
    }

    public function data()
    {
        $get_collections = (new Collection)->all();
        $sales = (new Product)->whereWithOperation("discount", ">" , "0.00")->select();
        if(!is_array($sales)) {
            $sales = array($sales);
        }
        
        static::$data = array( 
            "collections" => $get_collections,
            "sales" => $sales
        );
    }

    public function index(Request $request){

        
        return view("index", static::$data);
    }

    public function collection(Request $request)
    {

        $breadcumb = array();

        $slug = $request->param["collection"];
        $collection = (new Collection)->where("slug", $slug)->get();

        if($collection) {

            $product = new Product;

            $products = $product->where("collection", $collection->id)->all();
            if(!is_array($products)) {$products = null;}

            self::$data["heading"] = $collection->name;
            self::$data["count"] = $product->where("collection", $collection->id)->count();
            self::$data["brands"] = $product->where("collection", $collection->id)->groupBy("brand")->all();
            self::$data["categories"] = (new Category)->where("collection", $collection->id)->all();
            self::$data["url_path"] = "/collections/$collection->slug/";


            $link = "/collections/".$collection->slug;
            array_push($breadcumb, array($collection->name => $link));

            if(isset($request->param["category"])) 
            {

                $slug = $request->param["category"];

                $category = (new Category)->where("slug", $slug)->get();

                $products = $product->where("category", $category->id)->all();
                if(!is_array($products)) {$products = null;}

                self::$data["heading"] .= " - $category->name";
                self::$data["count"] = $product->where("category", $category->id)->count();
                self::$data["brands"] = $product->where("category", $category->id)->groupBy("brand")->all();
                self::$data["categories"] = (new SubCategory)->where("category", $category->id)->all();
                self::$data["url_path"] = "/collections/$collection->slug/$category->slug/";
                // self::$data["colors"] = 


                $link = "/collections/".$collection->slug."/".$category->slug;
                array_push($breadcumb, array($category->name => $link));

            }

            self::$data["breadcumb"] = $breadcumb;
            self::$data["products"] = $products;
            return view("collection", static::$data);

        }

        return error404();

    }

    public function cart()
    {
        return view("cart");
    }

    public function checkout()
    {
        return view("checkout");
    }

    public function contact()
    {
        return view("contact");
    }

    public function details()
    {
        return view("details");
    }

}