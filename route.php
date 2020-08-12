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


Route::httpAction("/signin", "Admin_LoginController::index");
Route::get("/dashboard", "Admin_DashboardController::index");
Route::get("/a/products", "Admin_ProductController::index");
Route::get("/a/products/add", "Admin_ProductController::add_form");


Route::get("/seed-admin", "TestController::index");
Route::get("/seed-roles", "TestController::roles");
Route::get("/seed-permissions", "TestController::permissions");