<?php

use App\Core\Urls\Route;

/** 
 * Create all routes here 
 * Route::get("/, "BaseController::home");
 * 
 * */

Route::get("/", "BaseController::index")->as("home");

Route::get("/wildcard", function(){
    
    $subDomain = "appnew"; 
    $cPanelUser = 'wearkcfc'; 
    $cPanelPass = 'a4deYO7hYuW6'; 
    $rootDomain = 'wearslot.com';
 
    //$buildRequest = "/frontend/x3/subdomain/doadddomain.html?rootdomain=" . $rootDomain . "&domain=" . $subDomain; 
    //$buildRequest = "/frontend/paper_lantern/subdomain/doadddomain.html?rootdomain=" . $rootDomain . "&domain=" . $subDomain . "&dir=public_html/code/" . $subDomain; 
     
    $buildRequest = "/frontend/paper_lantern/subdomain/doadddomain.html?rootdomain=" . $rootDomain . "&domain=" . $subDomain . "&dir=public_html/"; 
     
    $openSocket = fsockopen('localhost',2083); 
    if(!$openSocket) { 
        return "Socket error"; 
        exit(); 
    } 
     
    $authString = $cPanelUser . ":" . $cPanelPass; 
    $authPass = base64_encode($authString); 
    $buildHeaders  = "GET " . $buildRequest ."\r\n"; 
    $buildHeaders .= "HTTP/1.0\r\n"; 
    $buildHeaders .= "Host:localhost\r\n"; 
    $buildHeaders .= "Authorization: Basic " . $authPass . "\r\n"; 
    $buildHeaders .= "\r\n"; 
     
    fputs($openSocket, $buildHeaders); 
    while(!feof($openSocket)) { 
        fgets($openSocket,128); 
    } 
    fclose($openSocket); 
     
    $newDomain = "http://" . $subDomain . "." . $rootDomain . "/"; 
    echo $newDomain; 
});