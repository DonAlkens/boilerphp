<?php

$Cores = [
    'router' => [
        "routes" => "core/engine/router/route.php",
        "request" =>  "core/engine/router/request.php",
        "response" =>  "core/engine/router/response.php"
    ],

    "middlewares" => [
        "session" => "core/engine/middlewares/session.php",
        "cookies" => "core/engine/middlewares/cookies.php"
    ],

    "database"  => [
        "path" => "core/database/db.schema.php",
        "config" => "core/database/db.config.php",
        "migrations" => "core/database/migrations.php"
    ],

    "dependecies" => [
        "controller" => "core/action/controller.php",
        "template" => "core/engine/template/template.php",
    ],

    "helpers" => [
        "fs" => "core/helper/fs.php"
    ],
];



$dbConnection = [
    'HOST' => 'localhost',
    'PORT' => '',
    'USER' => 'root',
    'PASSWORD' => '',
    'DBNAME' => '',
    'CHARSET' => 'UTF-8'
];


$viewPath = "views";
$viewEngine = "fish";
$extension = "html";







