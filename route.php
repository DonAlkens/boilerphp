<?php

use App\Core\Urls\Route;

/** 
 * @create all routes here 
 * Route::get("/, "BaseController::home");
 * */

# Base
Route::get("/", "BaseController::home");

Route::get("/client", function($request) {
    return view("index");
});