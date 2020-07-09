<?php

use App\Action\Urls\Controller;


# Create all action method in the Controller class
# all method from the must must return
# either a view or a content
/** 
 * @param 'optional' [Request $request]
 * # used when an action is used on a post request
 * @example creating an action method
 * public function index(){
 * @param 'filename' in the views folder
 *     return view("index");
 * }
 * */ 

class ProductController extends Controller {

    public function __construct()
    {
        
    }

    public function index()
    {
        return view("product/index");
    }

}