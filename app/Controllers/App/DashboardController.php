<?php

namespace App\Action\Urls\Controllers\App;


use App\Action\Urls\Controllers\Controller;
use App\Core\Urls\Request;
use App\User;
use Auth;
use Cookie;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class DashboardController extends Controller {

    public function __construct()
    {
        if(Cookie::fetch("_apuid"))
        {
            $id = Cookie::fetch("_apuid");
            $user = (new User)->find($id);
            if($user->blocked != 1) { Auth::login($user); }
        }
        else 
        {
            Auth::logout();
        }
 
        $this->hasAuthAccess("auth", "https://app.".env('DOMAIN_NAME')."/sign-in?continue=".url());
        
    }

    public function index() {
        
        return view("app/index");
    }

}