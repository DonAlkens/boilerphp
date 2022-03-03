<?php

use App\Core\Urls\Route;

/** 
 * Create all routes here
 * ----------------------------
 * use Route::loadRoutes() to load other route files 
 * 
 * ---------------------------
 * 
 * Happy coding :) 
 * */

Route::get("/", "BaseController::index")->as("home");