<?php

use App\Core\Urls\Route;
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

// Route::get('/access', function() {
//     $token = (new User)->find(1)->createAccessToken('my_token');
//     return print_r($token);
// });

// Route::protected('Authorization:Bearer', function(){
//     Route::get('/protection', function(){
//         return Json(["user" => auth()->id]);
//     });
// });