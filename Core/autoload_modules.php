<?php 
namespace App\Core\Modules;

class AppModules {

    public $modules =  array(
    
        "Admin" => [
            "Admin::Auth"
        ],
    
        "router" => [
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
    
        "middlewares" => [
            "Engine::Middlewares::Session",
            "Engine::Middlewares::Cookies",
        ],
    
        "dependencies" => [
            "Actions::Controller",
            "Engine::Template::Template",
        ],
    
        "helpers" => [
            "Helper::Fs",
            "Helper::Hash",
        ]
    );

    public function addModule(array $module) {
        array_push($this->modules, $module);
    }
}
