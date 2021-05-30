<?php 
namespace App\Core\Modules;

class AppModules {

    public $configs = 
    [
        "configs::App",
        "configs::ErrorsConfig",
        "configs::RoutesConfig",
        "configs::ViewsConfig",
        "configs::MailConfig",
    ];

    public $modules =  
    [
        "middlewares" => [
            "Engine::Middlewares::Session",
            "Engine::Middlewares::Cookie",
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
            "Helpers::auth",
            "Helpers::app"
        ],
    
        "database" => [
            "Database::DataTypes",
            "Database::QueryBuilder",
            "Database::Connection",
            "Database::Schema",
            "Database::Relations",
            "Database::Model",
            "Database::Migration::ColumnDefination",
            "Database::Migration::Diagram",
            "Database::Migration::Table",
            "Database::Migration::Migration",
            "Database::Console::MigrationReflection"
        ],

        "messages" => [
            "Messages::PHPMailer::src::PHPMailer",
            "Messages::PHPMailer::src::SMTP",
            "Messages::PHPMailer::src::Exception",
            "Messages::Mail::MailBuilderInterface",
            "Messages::Mail::MailBuilder",
            "Messages::Mail::MailSender",
            "Messages::Mail::Mail",
            "Messages::Notification::NotifyBuilderInterface",
            "Messages::Notification::Notify",
            "Messages::Notification::Notification"
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
        ],

        "support" => [
            "Support::Device::Browser"
        ]

    ];

    public $router_modules =  
    [
        "Engine::Router::Validator",
        "Engine::Router::Route",
        "Engine::Router::Request",
        "Engine::Router::Response",
    ];

    public $socket_modules = 
    [
        "Engine::Socket::WebSocketEventsInterface",
        "Engine::Socket::WsServer",
        "Engine::Socket::WebSocket"
    ];

    public $user_modules = [];


    public function __construct()
    {
        $get_modules_file = fopen(".modules", "r");
        if ($get_modules_file) 
        {
            while (!feof($get_modules_file)) 
            {
                $path = fgets($get_modules_file);
                $this->addModule($path);
            }
        }
    }

    public function addModule($module) 
    {
        if(!empty($module))
        {
            array_push($this->user_modules, $module);
        }
    }
}
