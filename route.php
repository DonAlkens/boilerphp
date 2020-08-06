<?php

use App\Core\Urls\Route;

/** 
 * @create all routes here 
 * Route::get("/index", "BaseController::home");
 * Route::post("/index", "BaseController::home");
 * */


Route::httpAction("/","HomeController::index");


Route::subdomain("admin", function() {

    Route::get("/", "HomeController::yeah");
    Route::get("/yet", "AdminController::index");

});