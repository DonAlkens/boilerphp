<?php 
namespace App\Core\Modules;

class AppModules {

    public $configs = [

        "Config::App",
        "Config::ErrorsConfig",
        "Config::RoutesConfig",
        "Config::ViewsConfig",
        "Config::MailConfig",
        
    ];

    public $modules =  [

        "middlewares" => [
            "Engine::Middlewares::Session",
            "Engine::Middlewares::Cookies",
        ],

        "admin" => [
            "Admin::Authentication",
            "Admin::AuthProvider",
            "Admin::Door",
            "Admin::Auth"
        ],

        "helpers" => [
            "Helpers::forms",
            "Helpers::routes",
            "Helpers::views",
            "Helpers::auth"
        ],
    
        "database" => [
            "Database::DataTypes",
            "Database::QueryBuilder",
            "Database::Connection",
            "Database::Diagram",
            "Database::Schema",
            "Database::Relations",
            "Database::Model", 
            "Database::Migration",
            "Database::Config",
        ],
    
        "dependencies" => [
            "Actions::BaseController",
            "Actions::Controller",
            "Engine::Template::Template",
        ],
    
        "filesystem" => [
            "FileSystem::Fs",
        ],

        "hash" => [
            "Hash::Hash",
        ]

    ];

    public $router_modules =  [

        "Engine::Router::Validator",
        "Engine::Router::Route",
        "Engine::Router::Request",
        "Engine::Router::Response",

    ];



    public function addModule(array $module) 
    {
        array_push($this->modules, $module);
    }
}
