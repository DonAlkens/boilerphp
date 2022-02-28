<?php

use App\Core\Urls\Route;
use App\User;

/** 
 * Create all routes here
 * ----------------------------
 * use Route::loadRoutes() to load other route files 
 * 
 * ---------------------------
 * 
 * Happy coding :) 
 * */

// $user = (new User)->find(1);
Route::get("/home", "BaseController::index")->as("home");

// Route::subdomain("*", function(){

//     Route::get("/", "BaseController::home")->as("home2");

// });