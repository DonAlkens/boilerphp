<?php

use App\Core\Urls\Route;

/** 
 * @create all routes here 
 * Route::get("/, "BaseController::home");
 * */

 # Base
Route::httpAction("/","HomeController::index");
Route::httpAction("/all-collections", "HomeController::collections");
Route::get("/super-sales", "HomeController::super_sales");
Route::get("/brand/{name:string}", "HomeController::brand");
Route::httpAction("/collections/{collection:string}", "HomeController::shop");
Route::httpAction("/collections/{collection:string}/{category:string}", "HomeController::shop");
Route::httpAction("/collections/{collection:string}/{category:string}/{sub:string}", "HomeController::shop");
Route::httpAction("/pd/{product:string}/{id:string}", "HomeController::details");
Route::httpAction("/cart", "HomeController::cart");


# Secure Checkout
Route::httpAction("/checkout", "SecureCheckout::index");



# Product Controller Routes
Route::post("/pd/var/images/{product:int}", "ProductController::get_var_images");
Route::post("/pd/get-var-information/{product:int}", "ProductController::get_var_information");
Route::post("/pd/carting-data", "ProductController::get_carting_data");
Route::post("/pd/add-to-cart", "ProductController::add_to_cart");
Route::get("/pd/cart-widget", "ProductController::cart_widget");
Route::get("/pd/carting-response/{item:int}", "ProductController::cart_response");
Route::post("/pd/cart/remove", "ProductController::remove_item");
Route::get("/pd/cart-removed", "ProductController::remove_response");
Route::post("/pd/update-item-quantity", "ProductController::increment");



# Customer Auth Routes
Route::httpAction("/sign-in", "AuthController::sign_in");
Route::httpAction("/sign-in/rd/{location:string}", "AuthController::sign_in");

Route::httpAction("/sign-up", "AuthController::sign_up");
Route::httpAction("/sign-up/rd/{location:string}", "AuthController::sign_up");

Route::get("/uac/logout", "AuthController::logout");

# Customer Account Routes
Route::get("/uac/dashboard", "UserController::index");



# Admins
Route::subdomain("admin", function(){

    Route::httpAction("/", "Admin_LoginController::index");
    Route::get("/a/logout", "Admin_LoginController::logout");
    Route::get("/dashboard", "Admin_DashboardController::index");
    
    Route::get("/a/products", "Admin_ProductController::index");
    Route::get("/a/products/add", "Admin_ProductController::add_form");
    Route::post("/a/product/create_variations_list_options", "Admin_ProductController::create_variation_list_forms");
    Route::get("/a/products/view/{id:int}", "Admin_ProductController::view");
    Route::get("/a/products/edit/{id:int}", "Admin_ProductController::edit");
    Route::get("/a/products/delete/{id:int}", "Admin_ProductController::delete");
    Route::get("/a/products/catalogue", "Admin_ProductController::catalogue");
    
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
    Route::get("/api/a/variation/{id:int}","Api_Variation::get_variation_details");
    Route::post("/api/a/edit-variation", "Api_Variation::edit_variation");
    Route::post("/api/a/delete-variation", "Api_Variation::delete_variation");
    
    Route::post("/api/a/add-collections", "Api_Collection::add");
    Route::get("/api/a/get-collections", "Api_collection::get_collections");
    Route::get("/api/a/get-collections-table", "Api_collection::get_collections_table");
    Route::get("/api/a/collection/{id:int}", "Api_collection::get_collection_details");
    Route::post("/api/a/edit-collection", "Api_Collection::edit_collection");
    Route::post("/api/a/delete-collection", "Api_Collection::delete_collection");
    
    Route::post("/api/a/add-categories", "Api_Collection::add_categories");
    Route::get("/api/a/get-categories", "Api_Collection::get_categories");
    Route::get("/api/a/collection/category/{id:int}", "Api_Collection::get_category_details");
    Route::get("/api/a/get-categories-table", "Api_Collection::get_categories_table");
    Route::get("/api/a/get-categories/{collection:int}", "Api_Collection::get_collection_categories");
    Route::post("/api/a/collection/edit-category", "Api_Collection::edit_category");
    Route::post("/api/a/collection/delete-category", "Api_Collection::delete_category");
    
    Route::get("/api/a/get-subcategories", "Api_Collection::subcategories");
    Route::get("/api/a/get-subcategories/{category:int}", "Api_Collection::get_subcategories");
    
    Route::post("/api/a/add-products", "Api_Product::add");
    Route::get("/api/a/get-products", "Api_Product::get_products");
    Route::get("/api/a/get-products-table", "Api_Product::get_prouducts_table");
    Route::post("/api/a/edit-product-information/{id:int}", "Api_Product::edit_product_information");
    Route::post("/api/a/edit-product-images/{id:int}", "Api_Product::edit_product_images");
    Route::post("/api/a/edit-product-variations/{id:int}", "Api_Product::edit_product_variations");
    Route::post("/api/a/edit-product-settings/{id:int}", "Api_Product::edit_product_settings");
    
    Route::post("/api/a/remove-images/{id:int}", "Api_Product::remove_images");
    Route::post("/api/a/remove-variations/{product:int}/{variation:int}", "Api_Product::remove_variations");
    
    
    Route::httpAction("/test_form", "TestController::test_form");
});
