<?php

use App\Core\Urls\Route;

/** 
 * @create all routes here 
 * Route::get("/, "BaseController::home");
 * */

Route::httpAction("/","HomeController::index");
Route::httpAction("/category", "HomeController::category");
Route::httpAction("/product", "HomeController::details");
Route::httpAction("/cart", "HomeController::cart");
Route::httpAction("/checkout", "HomeController::checkout");
Route::httpAction("/contact", "HomeController::contact");