<?php

use App\Admin\Auth;
use App\Helpers\Fs;
use App\Action\Urls\Controller;
use App\User;

# use App\Core\Engine\Router\Request;

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

    public function __construct()
    {
        
    }

    public function home() {
        
        return view("base/welcome");
    }

    public function contact() {
        echo $_ENV["FB_APP_SECRET_KEY"];
    }

}