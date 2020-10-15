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
Route::httpAction("/order/payment", "SecureCheckout::card_payment");
Route::httpAction("/order/payment/p/verify", "SecureCheckout::paystack");
Route::httpAction("/order/payment/f/verify", "SecureCheckout::flutterwave");


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

#Customer Order Routes
Route::get("/uac/orders", "Customer\OrderController::index");
Route::get("/uac/order/success/{order:int}", "Customer\OrderController::success");
Route::get("/uac/order/details/{order:int}", "Customer\OrderController::details");

Route::get("/uac/delivery-address", "Customer\AddressController::index");
Route::httpAction("/uac/add-delivery-address", "Customer\AddressController::add_new");


# Admins
Route::subdomain("admin", function(){

    Route::httpAction("/", "Admin\LoginController::index");
    Route::httpAction("/signin", "Admin\LoginController::index");
    Route::get("/a/logout", "Admin\LoginController::logout");

    # Dashboard
    Route::get("/dashboard", "Admin\DashboardController::index");

    # Customers
    Route::get("/a/customers", "Admin\CustomerController::index");
    Route::get("/a/customers/view/{id:int}", "Admin\CustomerController::view");
    
    
    # Products
    Route::get("/a/products", "Admin\ProductController::index");
    Route::get("/a/products/add", "Admin\ProductController::add_form");
    Route::post("/a/product/create_variations_list_options", "Admin\ProductController::create_variation_list_forms");
    Route::get("/a/products/view/{id:int}", "Admin\ProductController::view");
    Route::get("/a/products/edit/{id:int}", "Admin\ProductController::edit");
    Route::get("/a/products/delete/{id:int}", "Admin\ProductController::delete");
    Route::get("/a/products/hide/{id:int}", "Admin\ProductController::hide");
    Route::get("/a/products/catalogue", "Admin\ProductController::catalogue");
    Route::get("/a/products/pending", "Admin\ProductController::pending");
    Route::get("/a/products/hidden", "Admin\ProductController::hidden");
    

    # Collections
    Route::get("/a/collections", "Admin\CollectionsController::index");
    Route::get("/a/collections/add", "Admin\CollectionsController::add_form");
    Route::get("/a/collection/categories", "Admin\CollectionsController::categories");
    Route::get("/a/collection/categories/add", "Admin\CollectionsController::add_category_form");
    

    # Variations
    Route::get("/a/variations", "Admin\VariationController::index");
    Route::get("/a/variations/add", "Admin\VariationController::add_form");


    # Orders 
    Route::get("/a/orders/create", "Admin\OrderController::create");
    Route::get("/a/orders/new", "Admin\OrderController::new");
    Route::get("/a/orders/confirmed", "Admin\OrderController::confirmed");
    Route::get("/a/orders/processed", "Admin\OrderController::processed");
    Route::get("/a/orders/shipped", "Admin\OrderController::shipped");
    Route::get("/a/orders/completed", "Admin\OrderController::completed");
    Route::get("/a/orders/cancelled", "Admin\OrderController::cancelled");
    Route::get("/a/orders/returned", "Admin\OrderController::returned");
    Route::get("/a/orders/view/{order:int}", "Admin\OrderController::details");


    # Order Items 
    Route::get("/a/order/items/confirmed", "Admin\OrderController::confirmed_items");
    Route::httpAction("/a/order/items/shipped", "Admin\OrderController::shipped_items");
    Route::get("/a/order/items/pending", "Admin\OrderController::pending_items");


    # Vendors
    Route::get("/a/vendors/approved", "Admin\VendorController::approved");
    Route::get("/a/vendors/pending", "Admin\VendorController::pending");
    Route::get("/a/vendors/blocked", "Admin\VendorController::blocked");
    Route::get("/a/vendors/view/{id:int}", "Admin\VendorController::view");

    # Transactions
    Route::get("/a/payments/transactions", "Admin\TransactionController::transactions");
    Route::httpAction("/a/payments/withdrawals/new", "Admin\TransactionController::new_withdrawals");
    Route::get("/a/payments/withdrawals/processed", "Admin\TransactionController::processed_withdrawals");
    
    
    Route::get("/seed-admin", "TestController::index");
    Route::get("/seed-roles", "TestController::roles");
    Route::get("/seed-permissions", "TestController::permissions");
    
    
    #Api Routes
    Route::post("/api/a/add-variations", "Api\VariationController::add");
    Route::get("/api/a/get-variations", "Api\VariationController::get_variations");
    Route::get("/api/a/get-variations-table", "Api\VariationController::get_variations_table");
    Route::get("/api/a/variation/{id:int}", "Api\VariationController::get_variation_details");
    Route::post("/api/a/edit-variation", "Api\VariationController::edit_variation");
    Route::post("/api/a/delete-variation", "Api\VariationController::delete_variation");
    

    Route::post("/api/a/add-collections",  "Api\CollectionController::add");
    Route::get("/api/a/get-collections", "Api\CollectionController::get_collections");
    Route::get("/api/a/get-collections-table", "Api\CollectionController::get_collections_table");
    Route::get("/api/a/collection/{id:int}", "Api\CollectionController::get_collection_details");
    Route::post("/api/a/edit-collection",  "Api\CollectionController::edit_collection");
    Route::post("/api/a/delete-collection",  "Api\CollectionController::delete_collection");
    

    Route::post("/api/a/add-categories", "Api\CollectionController::add_categories");
    Route::get("/api/a/get-categories", "Api\CollectionController::get_categories");
    Route::get("/api/a/collection/category/{id:int}", "Api\CollectionController::get_category_details");
    Route::get("/api/a/get-categories-table", "Api\CollectionController::get_categories_table");
    Route::get("/api/a/get-categories/{collection:int}", "Api\CollectionController::get_collection_categories");
    Route::post("/api/a/collection/edit-category", "Api\CollectionController::edit_category");
    Route::post("/api/a/collection/delete-category", "Api\CollectionController::delete_category");
    

    Route::get("/api/a/get-subcategories",  "Api\CollectionController::subcategories");
    Route::get("/api/a/get-subcategories/{category:int}",  "Api\CollectionController::get_subcategories");
    

    Route::post("/api/a/add-products", "Api\ProductController::add");
    Route::get("/api/a/get-products", "Api\ProductController::get_products");
    Route::get("/api/a/get-products-table", "Api\ProductController::get_products_table");
    Route::get("/api/a/get-pending-products-table", "Api\ProductController::get_pending_products_table");
    Route::get("/api/a/get-hidden-products-table", "Api\ProductController::get_hidden_products_table");
    Route::post("/api/a/edit-product-information/{id:int}", "Api\ProductController::edit_product_information");
    Route::post("/api/a/edit-product-images/{id:int}", "Api\ProductController::edit_product_images");
    Route::post("/api/a/edit-product-variations/{id:int}", "Api\ProductController::edit_product_variations");
    Route::post("/api/a/edit-product-settings/{id:int}", "Api\ProductController::edit_product_settings");


    Route::post("/api/a/approve-product", "Api\ProductController::approve");
    Route::post("/api/a/disapprove-product", "Api\ProductController::disapprove");
    Route::post("/api/a/hide-product", "Api\ProductController::hide");
    Route::post("/api/a/show-product", "Api\ProductController::show");
    

    Route::post("/api/a/remove-images/{id:int}", "Api\ProductController::remove_images");
    Route::post("/api/a/remove-variations/{product:int}/{variation:int}", "Api\ProductController::remove_variations");


    Route::get("/api/a/orders/create", "Api\OrderController::create");
    Route::get("/api/a/get-new-orders-table", "Api\OrderController::new");
    Route::get("/api/a/get-confirmed-orders-table", "Api\OrderController::confirmed");
    Route::get("/api/a/get-processed-orders-table", "Api\OrderController::processed");
    Route::get("/api/a/get-shipped-orders-table", "Api\OrderController::shipped");
    Route::get("/api/a/get-completed-orders-table", "Api\OrderController::completed");
    Route::get("/api/a/get-cancelled-orders-table", "Api\OrderController::cancelled");
    Route::get("/api/a/get-returned-orders-table", "Api\OrderController::returned");
    
    Route::get("/api/a/get-shipped-order-items-table", "Api\OrderController::get_shipped_order_items");
    Route::get("/api/a/get-confirmed-order-items-table", "Api\OrderController::get_confirmed_order_items");
    Route::get("/api/a/get-pending-order-items-table", "Api\OrderController::get_pending_order_items");
    Route::get("/api/a/items/{id:int}", "Api\OrderController::get_item");


    Route::post("/api/a/confirm-order", "Api\OrderController::confirm");
    Route::post("/api/a/process-order", "Api\OrderController::process");
    Route::post("/api/a/ship-order", "Api\OrderController::ship");
    Route::post("/api/a/cancel-order", "Api\OrderController::cancel");
    Route::post("/api/a/return-order", "Api\OrderController::return");
    Route::post("/api/a/complete-order", "Api\OrderController::complete");


    Route::get("/api/a/get-approved-vendors-table", "Api\VendorController::get_approved_vendors_table");
    Route::get("/api/a/get-pending-vendors-table", "Api\VendorController::get_pending_vendors_table");
    Route::get("/api/a/get-blocked-vendors-table", "Api\VendorController::get_blocked_vendors_table");

    
    Route::get("/api/a/get-customers-table", "Api\CustomerController::customers");
    Route::get("/api/a/get-transactions-table", "Api\TransactionController::logs");

    Route::get("/api/a/get-new-withdrawals-table", "Api\WithdrawalController::get_new_withdrawals");
    Route::get("/api/a/get-processed-withdrawals-table", "Api\WithdrawalController::get_paid_withdrawals");
    Route::get("/api/a/process/{id:int}", "Api\WithdrawalController::get_withdrawal");

    Route::httpAction("/test_form", "TestController::test_form");
});



# Vendors 
Route::subdomain("vendor", function(){

    Route::httpAction("/dashboard", "Vendor\HomeController::index");
    Route::httpAction("/login", "Vendor\AuthController::login");
    Route::httpAction("/register", "Vendor\AuthController::register");
    Route::httpAction("/verification", "Vendor\AuthController::verification");
    Route::get("/logout", "Vendor\AuthController::logout");
    

    # Products
    Route::get("/products", "Vendor\ProductController::index");
    Route::get("/products/add", "Vendor\ProductController::add_form");
    Route::post("/a/product/create_variations_list_options", "Vendor\ProductController::create_variation_list_forms");
    Route::get("/products/view/{id:int}", "Vendor\ProductController::view");
    Route::get("/products/edit/{id:int}", "Vendor\ProductController::edit");
    Route::get("/products/delete/{id:int}", "Vendor\ProductController::delete");
    Route::get("/products/hide/{id:int}", "Vendor\ProductController::hide");
    Route::get("/products/catalogue", "Vendor\ProductController::catalogue");
    Route::get("/products/pending", "Vendor\ProductController::pending");
    Route::get("/products/hidden", "Vendor\ProductController::hidden");
    

    # Orders 
    Route::get("/orders/pending", "Vendor\OrderController::pending");
    Route::get("/orders/shipped", "Vendor\OrderController::shipped");
    
    # Withdrawals
    Route::httpAction("/withdrawals", "Vendor\WithdrawalController::index");
    // Route::get("/withdrawals/new", "Vendor\WithdrawalController::withdraw");
    
    # Api Routes
    Route::post("/api/a/add-products", "Api\ProductController::add");
    Route::get("/api/a/get-products", "Api\ProductController::get_products");
    Route::get("/api/a/get-products-table/{vendor:int}", "Api\ProductController::get_products_table");
    Route::get("/api/a/get-pending-products-table/{vendor:int}", "Api\ProductController::get_pending_products_table");
    Route::get("/api/a/get-hidden-products-table/{vendor:int}", "Api\ProductController::get_hidden_products_table");
    
    Route::post("/api/a/edit-product-information/{id:int}", "Api\ProductController::edit_product_information");
    Route::post("/api/a/edit-product-images/{id:int}", "Api\ProductController::edit_product_images");
    Route::post("/api/a/edit-product-variations/{id:int}", "Api\ProductController::edit_product_variations");
    Route::post("/api/a/edit-product-settings/{id:int}", "Api\ProductController::edit_product_settings");
    

    Route::get("/api/a/get-vendors-shipped-orders-table", "Api\OrderController::get_vendor_shipped_orders");
    Route::get("/api/a/get-vendors-pending-orders-table", "Api\OrderController::get_vendor_pending_orders");
    Route::get("/api/a/items/{id:int}", "Api\OrderController::get_item");

    
    Route::post("/api/a/add-variations", "Api\VariationController::add");
    Route::get("/api/a/get-variations", "Api\VariationController::get_variations");
    Route::get("/api/a/get-variations-table", "Api\VariationController::get_variations_table");
    Route::get("/api/a/variation/{id:int}", "Api\VariationController::get_variation_details");
    Route::post("/api/a/edit-variation", "Api\VariationController::edit_variation");
    Route::post("/api/a/delete-variation", "Api\VariationController::delete_variation");
    
    
    Route::post("/api/a/add-collections",  "Api\CollectionController::add");
    Route::get("/api/a/get-collections", "Api\CollectionController::get_collections");
    Route::get("/api/a/get-collections-table", "Api\CollectionController::get_collections_table");
    Route::get("/api/a/collection/{id:int}", "Api\CollectionController::get_collection_details");
    Route::post("/api/a/edit-collection",  "Api\CollectionController::edit_collection");
    Route::post("/api/a/delete-collection",  "Api\CollectionController::delete_collection");
    
    
    Route::post("/api/a/add-categories", "Api\CollectionController::add_categories");
    Route::get("/api/a/get-categories", "Api\CollectionController::get_categories");
    Route::get("/api/a/collection/category/{id:int}", "Api\CollectionController::get_category_details");
    Route::get("/api/a/get-categories-table", "Api\CollectionController::get_categories_table");
    Route::get("/api/a/get-categories/{collection:int}", "Api\CollectionController::get_collection_categories");
    Route::post("/api/a/collection/edit-category", "Api\CollectionController::edit_category");
    Route::post("/api/a/collection/delete-category", "Api\CollectionController::delete_category");
    
    
    Route::get("/api/a/get-subcategories",  "Api\CollectionController::subcategories");
    Route::get("/api/a/get-subcategories/{category:int}",  "Api\CollectionController::get_subcategories");
    
    Route::get("/api/a/get-vendors-withdrawals-table", "Api\WithdrawalController::get_vendor_withdrawals");
    
});
