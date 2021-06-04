<?php

use App\Core\Urls\Route;

/** 
 * Create all routes here 
 * Route::get("/, "BaseController::home");
 * 
 * */

Route::get("/", "BaseController::index")->as("home");