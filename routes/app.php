<?php

use App\Core\Urls\Route;

Route::subdomain("app", function(){

    Route::get("/", "App\DashboardController::index")->as("app_index");

    // Auth Routes
    Route::httpAction("/sign-in", "App\AuthController::sign_in", "sign_in");
    Route::httpAction("/sign-up", "App\AuthController::sign_up", "sign_up");

    Route::get("/logout", "AuthController::logout");
 
    Route::httpAction("/step/verification", "AuthController::verification");
    Route::get("/verify/{sha:string}/{id:int}", "AuthController::verify");
 
 
    Route::httpAction("/forget-password", "AuthController::forgot");
    Route::get("/fconfirmed", "AuthController::fconfirmed");
    Route::httpAction("/change-password/{id:int}/{code:string}", "AuthController::change");




    // Contacts 
    Route::get("/contacts", "App\ContactController::index");
});