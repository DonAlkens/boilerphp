<?php

use App\Core\Urls\Route;

/** 
 * @create all routes here 
 * Route::get("/, "BaseController::home");
 * */

Route::httpAction("/","HomeController::index");

Route::subdomain("admins", function(){
    Route::httpAction("/","HomeController::index");
}); 