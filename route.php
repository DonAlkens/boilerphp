<?php

use App\Core\Urls\Route;

/** 
 * @create all routes here 
 * Route::get("/, "BaseController::home");
 * */

Route::httpAction("/", "Auth\UserController::index");