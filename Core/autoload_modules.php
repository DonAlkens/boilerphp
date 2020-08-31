<?php 
namespace App\Core\Modules;

class AppModules {

    public $configurations = array(
        "Config::ErrorsConfig",
        "Config::RoutesConfig",
        "Config::ViewsConfig",
        "Config::MailConfig",
    );

    public $modules =  array(

        "middlewares" => [
            "Engine::Middlewares::Session",
            "Engine::Middlewares::Cookies",
        ],

        "admin" => [
            "Admin::Authentication",
            "Admin::AuthProvider",
            "Admin::Door"
        ],

        "helpers" => [
            "Helpers::forms",
            "Helpers::routes",
            "Helpers::views",
            "Helpers::auth"
        ],
    
        "router" => [
            "Engine::Router::Validator",
            "Engine::Router::Route",
            "Engine::Router::Request",
            "Engine::Router::Response",
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
            "Actions::Controller",
            "Engine::Template::Template",
        ],
    
        "filesystem" => [
            "FileSystem::Fs",
        ],

        "hash" => [
            "Hash::Hash",
        ]
    );

    public function addModule(array $module) 
    {
        array_push($this->modules, $module);
    }
}
