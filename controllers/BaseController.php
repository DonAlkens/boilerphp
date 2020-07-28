<?php

use App\Action\Urls\Controller;
use App\Core\Engine\Request;

# Create all action method in the Controller class
# all method from the must must return
# either a view or a content
/** 
 * @param 'optional' [Request $req]
 * # used when an action is used on a post request
 * @example creating an action method
 * public function index(){
 * @param 'filename' in the views folder
 *     return view("index");
 * }
 * */ 

class BaseController extends Controller {

    public function home(Request $request) {
        return view("base/welcome");
    }

}