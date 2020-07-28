<?php

use App\Core\Engine\Route;

/** 
 * @create all routes here 
 * @using the mapRoute method from the Route Class
 * @param "type=array()"
 * @properties = url, method, action | type="string"
 * @example 
 * Route::mapRoute([
 *      "url" => "/",
 *      "method" => "get",
 *      "action" => "BaseController::index"
 * ]);
 * # Creating a route with parameter
 * Route::mapRoute([
 *      "url" => "/profile/{id:int}",
 *      "method" => "get",
 *      "action" => "BaseController::index"
 * ]);
 * 
 * # using method function
 * Route::get("/admin", "AdminController::create_template");
 * Route::post("/admin", "AdminController::create_template");
 * */


Route::get("/","BaseController::home");
Route::HttpAction("/contact","BaseController::contact");


Route::get("/testing", "TestController::testing");
Route::get("/ipaddress","UserController::getUserIp");