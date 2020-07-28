<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;

/** 
 * @param 'optional' [Request $request]
 * # used when an action is used on a post request
 * */ 

class BaseController extends Controller {

    public function __construct()
    {
        //$this->hasAuthAccess("user", "login");
    }

    public function home() 
    {
        return view("base/welcome");
    }

}