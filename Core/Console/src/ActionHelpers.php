<?php 

namespace Console\Support;

use App\FileSystem\Fs;
use App\Core\Database\Console\Support\MigrationReflection;
use App\Core\Database\Migration\Table;
use App\Core\Database\Schema;
use Console\Support\Interfaces\ActionHelpersInterface;


class ActionHelpers implements ActionHelpersInterface {

    public $commands = array(
        "create", "start", "db", "activate", "disable"
    );

    public $flags = array(
        "--m" => "model",
        "--a" => "all",
        "--c" => "controller",
        "--d" => "migration",
        "--s" => "socket"
    );

    public $db_flags = array(
        "--new" => "refresh",
        "--backup" => "backup",
    );

    public $configurations = array(
        "model" => "configiureModel",
        "controller" => "configureController",
        "migration" => "configureMigration",
        "notification" => "configureNotification",
        "socket" => "configureSocket"
    );

    public $db_configurations = array(
        "refresh" => "dropAllExistingTable",
    );

    public $paths = array(
        "model" => "./app/Models/",
        "view" => "./app/Views/",
        "controller" => "./app/Controllers/",
        "migration" => "./database/Migrations/",
        "notification" => "./app/Notifications/",
    );

    protected $specialTableChars = ["boy"];

    /**
     * Starts Application Development Server
     * 
     * @param $port - default port number is 8000
     * 
     * @return void
     */
    public function runServer($port) 
    {
        $server_command = "php -S localhost:".$port;
        print_r("Server listening on http://localhost:{$port}\n");
        exec($server_command);
    }

    /**
     * checks flag and action
     * for difference
     * @return bool
     */

    public function flagchecker($action, $flag) 
    {
        
        if($this->flags[$flag] == $action) 
        {
            echo "mis-usage of flag on create ". $action;
            return false;
        }

        return true;
    }

    public function flagHandler($name, $flag, $action) 
    {

        if($flag == "--a") 
        {
            foreach($this->flags as $flag => $task) 
            {
                if($task == $action || $task == "all") 
                {
                    continue;
                }

                $this->flagConfig($flag, $name);
            }
        } 
        else 
        {
            $this->flagConfig($flag, $name);
        }

    }

    public function flagConfig($flag, $name) 
    {
        $task = $this->flags[$flag];

        if($task == "controller") 
        { 
            $name .= "Controller"; 
        }

        $path = $this->path($task).$name.".php";

        if($task == "migration") 
        {
            $name = $this->tableFormating($name);

            $file_name = $name."_table.php";

            if($this->checkMigrationExistent($file_name)) 
            {
                echo "Migration already exists";
                return;
            }

            $path = $this->path($task).time()."_".$file_name;
        }

        $configuration = $this->configurations[$task];
        $this->$configuration($name, $path);

    }

    public function checkExistent($path) 
    {

        if(file_exists($path))
        { 
            return true;
        }
        return false;
    }

    public function path($name) 
    {
        return $this->paths[$name];
    }


    public function checkMigrationExistent($filename) 
    {

        $all_migrations_file = glob("./database/Migrations/*.php");
        if($all_migrations_file) 
        {
            foreach($all_migrations_file as $migration_file)
            {
                if($this->migrationFileNameChecker($migration_file, $filename))
                {
                    echo "Migration already exists";
                    exit;
                }
            }
        }
        return false;
    }


    public function migrationFileNameChecker($migration_file, $name_format)
    {
        $ex = explode("/", $migration_file);
        $exMfile = explode("_", end($ex));
        $filename = $exMfile[1]."_".$exMfile[2];

        if($filename == $name_format) {
            return true;
        }

        return false;
    }

    /**
     * usage: configures notification structure and inital setup
     * @param string notification_name
     * @param string notification_path
     * 
     * @return void;
     */

    public function configureNotification($notification_name, $notification_path)
    {
        $component_path = "./Core/Console/components/notification.component";

        if($this->readComponent($component_path)) 
        {
            $this->module = preg_replace("/\[Notification\]/",$notification_name, $this->component);
            if($this->writeModule($notification_path)) 
            {
                echo "$notification_name successfully created!\n";
                return true;
            }
            return false;
        }
    }

    /**
     * usage: configures model structure and inital setup
     * @param string model_name
     * @param string model_path
     * 
     * @return void;
     */

    public function configureModel($model_name, $model_path) 
    {
        $component_path = "./Core/Console/components/model.component";

        if($this->readComponent($component_path)) 
        {
            $this->module = preg_replace("/\[Model\]/",$model_name, $this->component);
            if($this->writeModule($model_path)) 
            {
                echo "$model_name model successfully created!\n";
                return true;
            }
            return false;
        }
    }

    
    /**
     * usage: configures migration structure and inital setup
     * @param string migration_name
     * @param string migration_path
     */
    public function configureMigration($migration_name, $migration_path) 
    {
        $component_path = "./Core/Console/components/migration.component";
        if($this->readComponent($component_path)) 
        {
            $class_name = ucfirst($migration_name);
            if(strpos($migration_name, "_"))
            {
                $e = explode("_", $migration_name);
                $new_cl_name = "";
                foreach($e as $piece) 
                {
                    $new_cl_name .= ucfirst($piece);
                }

                $class_name = $new_cl_name;
            }

            $this->module = preg_replace("/\[ClassName\]/",$class_name."Table", $this->component);
            
            $table_name = $migration_name;

            $this->module = preg_replace("/\[TableName\]/", strtolower($table_name), $this->module);

            if($this->writeModule($migration_path)) 
            {
                echo "$class_name migration successfully created!\n";
                return true;
            }
            return false;
        }
    }

    /**
     * usage: configures model structure and inital setup
     * @param string model_name
     * @param string model_path
     * 
     * @return void;
     */

    public function configureSocket($socket_name, $socket_path) 
    {
        $component_path = "./Core/Console/components/websocket/socket-skeleton.component";

        if($this->readComponent($component_path)) 
        {
            $this->module = preg_replace("/\[SocketName\]/",$socket_name, $this->component);
            if($this->writeModule($socket_path)) 
            {
                echo "$socket_name socket successfully created!\n";
                return true;
            }
            return false;
        }
    }

    /**
     * usage: formats table name and file name
     * @param string name
     * @return string table_name
     */
    public function tableFormating($name)
    {
        $format_name = str_split($name);
        $table_name = "";
        foreach($format_name as $key => $val) 
        {
            if(ctype_upper($val)) 
            {
                $table_name .= "_".strtolower($val);
                continue;
            }

            $table_name .= $val;
        }

        $table_name = trim($table_name, "_");

        $lastchar = strtolower(substr($table_name, -1));
        
        if($lastchar == "y" && !in_array($table_name, $this->specialTableChars)) {
            $table_name = substr($table_name, 0, (strlen($table_name) - 1))."ies";
        }
        else if($lastchar == "x" && !in_array($table_name, $this->specialTableChars)) {
            $table_name .= "es";
        }
        else if($lastchar != "s" && !in_array($table_name, $this->specialTableChars)) {
            $table_name .= "s";
        }

        return $table_name;
    }

    /**
     * usage: checkes  is controller has namespace prefix
     * @param string controller_name
     * @return string namespace
     */
    public function checkNamaspacePrefix($_name) 
    {
        if(strpos($_name, "\\") || strpos($_name, "/")) {

            $split = (strpos($_name, "/")) 
            ? explode("/", $_name) 
            : explode("\\", $_name);

            $_namespace = $split[0];
            $this->controller_name = $split[1];

            $folder = $this->path("controller").$_namespace;
            if(!Fs::is_active_directory($folder)) 
            {
                Fs::create_directory($folder);
            }
            
            $this->use_namespace = "\\".$_namespace;

            return true;
        }

        return false;
    } 


    /**
     * usage: configures controller structure and inital setup
     * @param string controller_name
     */
    public function configureController($controller_name, $controller_path) 
    {
        $component_path = "./Core/Console/components/controller.component";

        if($this->readComponent($component_path) !== "") 
        {

            if($this->checkNamaspacePrefix($controller_name)) {

                $this->component = preg_replace("/\[Namespace\]/", $this->use_namespace, $this->component);
                $this->component = preg_replace("/\[Controller_Base_Namespace\]/", 'use App\Action\Urls\Controllers\Controller;', $this->component);
                $controller_name = $this->controller_name;
            }
            else 
            {
                $this->component = preg_replace("/\[Namespace\]/", '', $this->component);
                $this->component = preg_replace("/\[Controller_Base_Namespace\]/", '', $this->component);
            }


            $this->module = preg_replace("/\[Controller\]/", $controller_name, $this->component);
            $view_folder = str_replace("controller", "", strtolower($controller_name));

            if(!$this->checkExistent($this->path("view").$view_folder)) 
            {
                mkdir($this->path("view").$view_folder);
            }
    
            $this->module = preg_replace("/\[View\]/", $view_folder, $this->module);
            if($this->writeModule($controller_path)) 
            {
                echo "$controller_name successfully created!\n";
                return true;
            }
            return false;
        }

    }


    /**
     * checks and returns command length
     * @param command 
     * @return int
     */
    public function getCommandLength(array $command)
    {
        return count($command);
    }


    /**
     * reads the component file and get the components structure
     * @param string component_file_path
     * @return string
     */
    public function readComponent($path)
    {
        $this->component = file_get_contents($path); return $this->component;
    }



    public function writeModule($path)
    {
        $module = fopen($path, "w+"); fwrite($module, $this->module); return fclose($module);
    }


    public function checkTableExists($table)
    {
        $migrationReflection = new MigrationReflection;
        $checking = $migrationReflection->query("SHOW TABLES");

        $tables = $checking->fetchAll();
        if($tables) 
        {
            foreach($tables as $key => $value) 
            {
                if($value["Tables_in_".$migrationReflection->getDbName()] == $table) 
                {
                    $state = true;
                    break;
                }
                else 
                {
                    $state = false;
                }
            }
        }
        else 
        {
            $state = false;
        }

        return $state;
    }

    public function dropAllExistingTable()
    {
        $migrationReflection = new MigrationReflection;
        $migrationReflection->clearTables();

        $tables = 0;

        $all_migrations_file = glob("./database/Migrations/*.php");
        
        if($all_migrations_file) 
        {  
            foreach($all_migrations_file as $migration_file)
            {
                $this->requireOnce($migration_file);

                $class = $this->migrationClass($migration_file);
                $class->out();

                $tables++;
            }

            echo "Dropped ".$tables." table(s)\n";
        }


        // $tables = $checking->fetchAll();
        // if($tables) 
        // {
        //     foreach($tables as $key => $value) 
        //     {
        //         foreach($value as $defination => $name) 
        //         {
        //             // Drop
        //             $migrationReflection->query("SET FOREIGN_KEY_CHECKS = 1; DROP TABLE IF EXISTS $name;");
        //             $migrationReflection->query("SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS $name;");

        //         }
        //     }

        //     echo "Dropped ".(count($tables) - 1)." table(s)\n";
        // }
    }

    public function newMigrationsChecker()
    {
        $this->new_migrations = array();
        $all_migrations_file = glob("./database/Migrations/*.php");
        
        if($all_migrations_file) 
        {
            foreach($all_migrations_file as $migration_file)
            {
                if($this->migrationWaitingMigrate($migration_file)) 
                {
                    array_push($this->new_migrations, $migration_file);
                }
            }
        }

        if(count($this->new_migrations) > 0)
        {
            return true;
        }

        return false;
    }


    public function migrationWaitingMigrate($migration_file)
    {
        $ex = explode("/", $migration_file);
        $migration = str_replace(".php", "", end($ex));

        if($this->isWaiting($migration))
        {
            return true;
        }

        return false;
    }

    public function isWaiting($migration) 
    {
        if((new MigrationReflection)->checkMigration($migration))
        {
            return false;
        }
        return true;
    }

    public function createMigrationsTable() 
    {   
        $migrationReflection = new MigrationReflection;
        return $migrationReflection->init();
    }

    public function runMigrations()
    {
        foreach($this->new_migrations as $migration) 
        {
            $this->requireOnce($migration);

            $_tableName = $this->mFileFormater($migration)["table"];
            $_fileName = $this->mFileFormater($migration)["file"];

            echo "Migrating {$_tableName}: {$_fileName}\n";
            
            $class = $this->migrationClass($migration);
            $class->in();
            
            $this->registerMigration($_fileName, 1);
            echo "Migrated {$_tableName}: {$_fileName}\n";
        }
        
        echo "Running migration alter queries...\n";
        $this->runMigrationAlters();

        echo "Migration completed successfully\n";
    }

    public function runMigrationAlters()
    {
        $alters = Table::getAlters();

        if(count($alters) > 0) 
        {
            foreach($alters as $query)
            {
                $migrationReflection = new MigrationReflection;
                $migrationReflection->query($query);
            }
        }
    }

    public function registerMigration($file, $version) 
    {
        $migrationReflection = new MigrationReflection;
        $migrationReflection->registerMigration(["migration" => $file, "version" => $version]);
    }

    public function requireOnce($filepath)
    {
        return require_once $filepath;
    }

    public function migrationClass($migration)
    {
        $class = $this->mFileFormater($migration)["class"];
        return new $class;
    }

    public function mFileFormater($migration) 
    {
        $split = explode("/", $migration);
        $ex = str_replace(".php", "", end($split));

        $exMfile = explode("_", $ex);
        array_shift($exMfile);

        $classname = "";
        $tablename = "";
        foreach($exMfile as $piece) 
        {
            $classname .= ucfirst($piece);
            $tablename .= ucfirst($piece)." "; 
        }

        $filename = $ex;

        return array("class" => $classname, "file" => $filename, "table" => $tablename);
    }

    public function migrationFlagHandler($flag)
    {
        if($flag != null) 
        {
            $flag_action = $this->db_flags[$flag];
            $configuration = $this->db_configurations[$flag_action];
            $this->$configuration();
        }
    }

    public function enableThirdPartyLibrary()
    {
        $component_path = "./Core/Console/components/enable-third-party.component";
        if($this->readComponent($component_path) !== "") 
        {
            $path = "./Core/app_loader.php";
            $this->module = $this->component;
            if($this->writeModule($path))
            {
                echo "Third party libray has been enabled!";
                return true;
            }

            echo "Process Failed!";
            return false;
        }
    }

    public function disableThirdPartyLibrary()
    {
        $component_path = "./Core/Console/components/disable-third-party.component";
        if($this->readComponent($component_path) !== "") 
        {
            $path = "./Core/app_loader.php";
            $this->module = $this->component;
            if($this->writeModule($path))
            {
                echo "Third party libray has been disabled!";
                return true;
            }

            echo "Process Failed!";
            return false;
        }
    }

    public function enableWebSocket($flag = null) {

        $component_path = "./Core/Console/components/websocket/socket-skeleton.component";
        $manager_path = "./Core/Console/components/websocket/socket.component";

        if(!$this->checkExistent("./socket")) {

            if($this->readComponent($manager_path)) {

                $this->module = $this->component;
    
                if($this->writeModule("./socket")) {
                    
                    # Create Default File
                    if($this->readComponent($component_path)) {

                        $socket_name = "Chat";

                        if($flag == "--name") {
                            $socket_name = ucfirst(end($argv)); 
                        }
                        
                        if(!is_dir("./Sockets")) { mkdir("./Sockets"); }

                        $this->module = preg_replace("/\[SocketName\]/", $socket_name, $this->component);
                        $path = "./Sockets/{$socket_name}.php";

                        if(!$this->checkExistent($path)) 
                        {
                            if($this->writeModule($path)) {

                            }
                        }

                    }

                    echo "Socket has been activated successfully.\n";
                    return true;
                }
            }
        }

        echo "Socket has already been activated...\n";
        return false;
    }

    public function disableWebSocket($flag = null) {

        if($this->checkExistent("./socket")) {
            if(Fs::delete("socket")){

            }

            echo "Socket as been deactivated!";
            return true;
        }

        return false;
    }
}