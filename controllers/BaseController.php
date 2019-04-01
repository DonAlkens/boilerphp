<?php

use App\Helpers\Fs;
use App\Action\Urls\Controller;
use App\Action\Urls\view;
use App\Core\Engine\Router\Request;

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

    public function index(){
        return view("index");
    }

    public function testing(){
        Fs::copy(".env","bundles/.env");
    }

    public function tour($request){

        if(isset($request->param["category"])){
            return view("build_mode");
        }

        return view("tour");
    }
}