<?php

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

use App\Action\Urls\Controller;

class TestController extends Controller {

    public function __construct()
    {
        // $this->hasAuthAccess("user", "/signin");
    }

    public function testing()
    {

        return content("base/order");
    }

}