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

Route::get("/", "BaseController::index")->as("home");