<?php

use App\Core\Urls\Route;
use App\Role;
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

Route::get("/", "BaseController::index")->as("index");
Route::get("/home", "BaseController::index")->as("home");
Route::get("/about", "BaseController::index")->as("about");