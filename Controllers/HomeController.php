<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;
use App\Admin\Auth;
use App\Admin\Door;
use App\Cart;
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
        $sales = (new Product)->whereWithOperation("discount", ">" , "0.00")->all();
        
        if(!is_array($sales) && $sales != null) { $sales = array($sales); }

        $brands = (new Product)->groupBy("brand")->orderBy("id", "DESC", 12)->all();
        $arrivals = (new Product)->orderBy("id", "DESC", 4)->all();
        
        static::$data = array( 
            "collections" => $get_collections,
            "sales" => $sales,
            "brands" => $brands,
            "arrivals" => $arrivals
        );

    }


    public static function filters(Request $request, $products) {

        $filter = false;

        $selected = array();

        if(isset($request->colors)) {
            $request->colors = str_replace("-", " ", $request->colors);
            $colors = self::$data["filter_colors"] = explode(",", $request->colors);

           foreach($products as $product) {

                if(in_array($product->color, $colors)) {

                    array_push($selected, $product);

                }
                else if($product->variations()) {

                    foreach($product->variations() as $var) {

                        $var_name = strtolower($var->variation()->name);
                        if($var_name == "color" || $var_name == "colors") {
                            if(in_array($var->name, $colors)) {

                                array_push($selected, $product);

                            }
                        }

                    }

                }

           }

        }

        if(isset($request->sizes)) {
            $request->sizes = str_replace("-", " ", $request->sizes);
            $sizes = self::$data["filter_sizes"] = explode(",", $request->sizes);

            if(count($selected) > 0) {
                $products = $selected; $selected = array();
            }

            foreach($products as $product) {

                if($product->variations()) {

                    foreach($product->variations() as $var) {

                        $var_name = strtolower($var->variation()->name);
                        if($var_name == "size" || $var_name == "sizes") {
                            if(in_array($var->name, $sizes)) {

                                array_push($selected, $product);

                            }
                        }

                    }

                }

           }

        }

        if(isset($request->brands)) {
            $request->brands = str_replace("-", " ", $request->brands);
            $brands = self::$data["filter_brands"] = explode(",", $request->brands);

            if(count($selected) > 0) {
                $products = $selected; $selected = array();
            }

            foreach($products as $product) {

                if(in_array($product->brand, $brands)) {

                    array_push($selected, $product);

                }

           }


        }

        return $selected;

    }


    public static function price_range($products) {

        $highest = $lowest = 0;

        if($products != null) {

            foreach($products as $product) {
    
                if($lowest == 0 && $highest == 0) {
                    $lowest = $product->price; $highest = $product->price;
                    continue;
                }
    
                if($product->price < $lowest) { $lowest = $product->price; }
                if($product->price > $highest) { $highest = $product->price; }
    
                if($product->price_range() != null) {
                    if($product->price_range()["lowest"] < $lowest) {  $lowest = $product->price_range()["lowest"]; }
                    if($product->price_range()["highest"] > $highest) {  $highest = $product->price_range()["highest"]; }
                }
    
            }
    
        }
        
        $prices = array("lowest" => $lowest, "highest" => $highest);
        return $prices;

    }

    public function index(Request $request){

        
        return view("index", static::$data);
    }

    public function collections() {

        return true;
    }

    public function best_sellers(Request $request) {

        
    }

    public function new_arrivals(Request $request) {

        
    }

    public function super_sales(Request $request) {

        $product = (new Product);

        self::$data["heading"] = "Super Sales";
        self::$data["count"] = (new Product)->whereWithOperation("discount", ">" , "0.00")->count();
        self::$data["brands"] = (new Product)->whereWithOperation("discount", ">" , "0.00")->groupBy("brand")->all();
        self::$data["categories"] = array();
        $cats = (new Product)->whereWithOperation("discount", ">" , "0.00")->groupBy("collection")->all();
        foreach($cats as $category) {
            array_push(self::$data["categories"], $category->collection());
        }

        self::$data["url_path"] = "/collections/";

        $products = (new Product)->whereWithOperation("discount", ">" , "0.00")->all();
        $from_variations = static::collate_from_variations($products);
        self::$data["colors"] = $from_variations["colors"];
        self::$data["sizes"] = $from_variations["sizes"];

        $breadcumb = array(array("Super Sales" => "/super-sales"));


        if(isset($request->filter)) { 
            
            $products = static::filters($request, $products); 
            self::$data["count"] = count($products);
        }


        self::$data["breadcumb"] = $breadcumb;
        self::$data["products"] = $products;
        self::$data["price_range"] = static::price_range($products);
        return view("collection", static::$data);
    }

    public function shop(Request $request)
    {

        $breadcumb = array();

        $slug = $request->param["collection"];
        $collection = (new Collection)->where("slug", $slug)->get();

        if($collection) {

            $product = new Product;

            $products = $product->where("collection", $collection->id)->orderBy("id", "DESC")->all();
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

                $products = $product->where("category", $category->id)->orderBy("id", "DESC")->all();
                if(!is_array($products)) {$products = null;}

                self::$data["heading"] .= " - $category->name";
                self::$data["count"] = $product->where("category", $category->id)->count();
                self::$data["brands"] = $product->where("category", $category->id)->groupBy("brand")->all();
                self::$data["categories"] = (new SubCategory)->where("category", $category->id)->all();
                self::$data["url_path"] = "/collections/$collection->slug/$category->slug/";


                $link = "/collections/".$collection->slug."/".$category->slug;
                array_push($breadcumb, array($category->name => $link));

                if(isset($request->param["sub"]))
                {

                    $slug = $request->param["sub"];

                    $sub_category = (new SubCategory)->where("slug", $slug)->get();

                    $products = $product->where("sub_category", $sub_category->id)->orderBy("id", "DESC")->all();
                    if(!is_array($products)) {$products = null;}

                    self::$data["heading"] .= " - $sub_category->name";
                    self::$data["count"] = $product->where("sub_category", $sub_category->id)->count();
                    self::$data["brands"] = $product->where("sub_category", $sub_category->id)->groupBy("brand")->all();
                    self::$data["url_path"] = "/collections/$collection->slug/$category->slug/";


                    $link = "/collections/".$collection->slug."/".$category->slug."/".$sub_category->slug;
                    array_push($breadcumb, array($sub_category->name => $link));

                }

            }


            // Collate sizes
            $from_variations = static::collate_from_variations($products);
            self::$data["colors"] = $from_variations["colors"];
            self::$data["sizes"] = $from_variations["sizes"];

            if(isset($request->filter)) { 

                $products = static::filters($request, $products); 
                self::$data["count"] = count($products);
            }
            
            self::$data["breadcumb"] = $breadcumb;
            self::$data["products"] = $products;
            self::$data["price_range"] = static::price_range($products);
            return view("collection", static::$data);

        }

        return error404();

    }

    public function cart(Request $request) {

        $cart = $product = null;
        $removed = false;

        if($request->method == "POST") {

            if(isset($request->remove)) {

                if( Session::get("auth") ) {

                    $product = (new Product)->where("id", $request->id)->select();

                    if((new Cart)->delete($request->id)) {

                        $removed = true;
                    }

                }
                else if( Session::get("cart") ) {

                    $cart = Session::get("cart"); $index = ($request->id - 1);
                    $product = (new Product)->where("id", $cart[$index]["product"])->select();

                    if( array_splice($cart, $index, 1) ) {

                        Session::set("cart", $cart);
                        $removed = true;
                    }
    
                }

            }

        }

        if( Session::get("auth") ) {

            $customer = Session::get("auth");
            $cart = (new Cart)->where("user", $customer)->all();
        }

        else if( Session::get("cart") ) {

            $cart_list = Session::get("cart");
            $cart = array();

            if(count($cart_list) > 0) {

                foreach($cart_list as $item) {

                    $cart_item = (new Cart);
                    $cart_item->product = $item["product"];
                    $cart_item->quantity = $item["quantity"];
                    $cart_item->variant = $item["variant"];

                    array_push($cart, $cart_item);

                }

            }

        }

        if($removed == true){

            self::$data["rproduct"] = $product;
            self::$data["removed"] = true;

        }

        self::$data["items"] = $cart;
        return view("cart", self::$data);
    }

    public function checkout()
    {
        return view("checkout");
    }

    public function details(Request $request)
    {
        $slug = $request->param["product"];
        $id = base64_decode($request->param["id"]);

        $breadcumb = array();

        $product = (new Product)->where(array("slug" => $slug, "id" => $id))->get();
        if($product) 
        {

            if($product->collection  != 0) {
                $link = "/collections/".$product->collection()->slug;
                array_push($breadcumb, array($product->collection()->name => $link));
            }

            if($product->category  != 0) {
                $link = "/collections/".$product->collection()->slug."/".$product->category()->slug;
                array_push($breadcumb, array($product->category()->name => $link));
            }

            if($product->sub_category  != 0) {
                $link = "/collections/".$product->collection()->slug."/".$product->category()->slug."/".$product->sub_category()->slug;
                array_push($breadcumb, array($product->sub_category()->name => $link));
            }


            $related = (new Product)
                        ->where(["category" => $product->category, "collection" => $product->collection])
                        ->orderBy("id", "DESC", 20)
                        ->all();

            
            self::$data["related"] = $related;
            self::$data["product"] = $product;
            self::$data["breadcumb"] = $breadcumb;
            return view("details", self::$data);

        }

        return error404();

    }

    public static function collate_from_variations($products) {

        $sizes = array();
        $colors = array();

        if(is_array($products)){
            foreach($products as $product) {
    
                if($product->color != null && !in_array($product->color, $colors)) {
                    array_push($colors, $product->color);
                }
    
                if($product->variations()) {
    
                    $variations = $product->variations();
    
                    foreach($variations as $variation) {
    
                        if($variation->variation()) {
    
                            $var_name = (new Product)->create_slug($variation->variation()->name);
        
                            if($var_name == "sizes" || $var_name == "size") {
                                if(!in_array($variation->name, $sizes)) {
                                    array_push($sizes, $variation->name);
                                }
                                
                            }
                            else if($var_name == "colors" || $var_name == "color") {
                                if(!in_array($variation->name, $colors)) {
                                    array_push($colors, $variation->name);
                                }
                            }
                        }
    
                    }
                }
    
            }
        }

        return array("colors" => $colors, "sizes" => $sizes);

    }

}