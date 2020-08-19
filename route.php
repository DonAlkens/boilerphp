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
Route::get("/a/logout", "Admin_LoginController::logout");
Route::get("/dashboard", "Admin_DashboardController::index");
Route::get("/a/products", "Admin_ProductController::index");
Route::get("/a/products/add", "Admin_ProductController::add_form");

Route::get("/a/collections", "Admin_CollectionsController::index");
Route::get("/a/collections/add", "Admin_CollectionsController::add_form");
Route::get("/a/collection/categories", "Admin_CollectionsController::categories");
Route::get("/a/collection/categories/add", "Admin_CollectionsController::add_category_form");
Route::get("/a/variations", "Admin_VariationController::index");
Route::get("/a/variations/add", "Admin_VariationController::add_form");


Route::get("/seed-admin", "TestController::index");
Route::get("/seed-roles", "TestController::roles");
Route::get("/seed-permissions", "TestController::permissions");


#Api Routes
Route::post("/api/a/add-variations","Api_Variation::add");
Route::get("/api/a/get-variations","Api_Variation::get_variations");
Route::get("/api/a/get-variations-table","Api_Variation::get_variations_table");

Route::post("/api/a/add-collections", "Api_Collection::add");
Route::get("/api/a/get-collections", "Api_collection::get_collections");
Route::get("/api/a/get-collections-table", "Api_collection::get_collections_table");

Route::post("/api/a/add-categories", "Api_Collection::add_categories");
Route::get("/api/a/get-categories", "Api_Collection::get_categories");
Route::get("/api/a/get-categories-table", "Api_Collection::get_categories_table");

Route::get("/api/a/get-subcategories/{category:int}", "Api_Collection::get_subcategories");

Route::post("/api/a/add-products", "Api_Product::add");