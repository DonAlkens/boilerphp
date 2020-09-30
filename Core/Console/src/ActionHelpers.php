<?php 

namespace Console\Support;

use App\Core\Database\Schema;
use App\FileSystem\Fs;
use Console\Support\Interfaces\ActionHelpersInterface;



class ActionHelpers implements ActionHelpersInterface {

    public $commands = array(
        "create", "start", "db"
    );

    public $flags = array(
        "--m" => "model",
        "--a" => "all",
        "--c" => "controller",
        "--d" => "migration"
    );

    public $db_flags = array(
        "--new" => "refresh",
    );

    public $configurations = array(
        "model" => "configiureModel",
        "controller" => "configureController",
        "migration" => "configureMigration",
        "notification" => "configureNotification"
    );

    public $db_configurations = array(
        "refresh" => "dropAllExistingTable",
    );

    public $paths = array(
        "model" => "./Models/",
        "controller" => "./Controllers/",
        "migration" => "./Migrations/",
        "notification" => "./Notification/",
    );

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

        $path = $this->paths[$task].$name.".php";

        if($task == "migration") 
        {
            $file_name = strtolower($name)."_table.php";

            if($this->checkMigrationExistent($file_name)) 
            {
                echo "Migration $name already exists";
                return;
            }

            $path = $this->paths[$task].time()."_".$file_name;
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


    public function checkMigrationExistent($filename) 
    {

        $all_migrations_file = glob("./Migrations/*.php");
        if($all_migrations_file) 
        {
            foreach($all_migrations_file as $migration_file)
            {
                if($this->migrationFileNameChecker($migration_file, $filename))
                {
                    return true;
                }
            }
        }
        return false;
    }


    public function migrationFileNameChecker($migration_file, $name_format)
    {
        $ex = explode("/", $migration_file);
        $exMfile = explode("_",$ex[2]);
        $filename = $exMfile[1]."_".$exMfile[2];

        if($filename == $name_format) {
            return true;
        }

        return false;
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
                echo "Model $model_name successfully created!\n";
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
            $lastChar = substr($migration_name, -1);
            if(strtolower($lastChar) != "s") 
            {
                $table_name .= "s";
            }

            $this->module = preg_replace("/\[TableName\]/", strtolower($table_name), $this->module);

            if($this->writeModule($migration_path)) 
            {
                echo "Migration $migration_name successfully created!\n";
                return true;
            }
            return false;
        }
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

            $folder = "./Controllers/".$_namespace;
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

            if(!$this->checkExistent("./Views/".$view_folder)) 
            {
                mkdir("./Views/".$view_folder);
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
        $schema = new Schema;
        $checking = $schema->query("SHOW TABLES");
        $tables = $checking->fetchAll();
        if($tables) {
            foreach($tables as $key => $value) 
            {
                if($value["Tables_in_".$schema->getDbName()] == $table) 
                {
                    return true;
                }
            }
        }
        return false;
    }

    public function dropAllExistingTable()
    {
        $schema = new Schema;
        $checking = $schema->query("SHOW TABLES");
        $tables = $checking->fetchAll();
        if($tables) 
        {
            foreach($tables as $key => $value) 
            {
                foreach($value as $defination => $name) 
                {
                    // Drop
                    $schema->query("SET FOREIGN_KEY_CHECKS = 1; DROP TABLE IF EXISTS $name;");
                    $schema->query("SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS $name;");

                }
            }

            echo "Dropped ".(count($tables) - 1)." table(s)\n";
        }
    }

    public function newMigrationsChecker()
    {
        $this->new_migrations = array();
        $all_migrations_file = glob("./Migrations/*.php");
        
        if($all_migrations_file) 
        {
            if($this->checkTableExists("migrations")) 
            {
                foreach($all_migrations_file as $migration_file)
                {
                    if($this->migrationWaitingMigrate($migration_file)) 
                    {
                        array_push($this->new_migrations, $migration_file);
                    }
                }
            }
            else 
            {
                $this->new_migrations = $all_migrations_file;
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
        $migration = str_replace(".php", "", $ex[2]);

        if($this->isWaiting($migration))
        {
            return true;
        }

        return false;
    }

    public function isWaiting($migration) 
    {
        $schema = new Schema;
        $schema->table = "migrations";
        $checking = $schema->where("migration", $migration)->get();

        if($checking) 
        {
            return false;
        }
        return true;
    }

    public function createMigrationsTable() 
    {
        
        $schema = new Schema;
        return $schema->run("CREATE TABLE IF NOT EXISTS migrations(
            `id` INT(9) NOT NULL AUTO_INCREMENT,
            `migration` VARCHAR(255) DEFAULT NULL,
            `version` INT(9) DEFAULT NULL,
            `created_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(`id`)
            )
        ");

    }


    public function runServer($port) 
    {
        $server_command = "php -S localhost:".$port;
        print_r("Server listening on http://127.0.0.1:".$port);
        exec($server_command);
    }


    public function runMigrations()
    {
        $alters = array();

        foreach($this->new_migrations as $migration) 
        {
            $this->requireOnce($migration);

            echo "Creating ".$this->mFileFormater($migration)["table"].": ".$this->mFileFormater($migration)["file"]."\n";
            
            $class = $this->migrationClass($migration);
            $class->create();
            array_push($alters, $class->alters);

            $this->registerMigration($this->mFileFormater($migration)["file"], 1);

            echo "Created ".$this->mFileFormater($migration)["table"].": ".$this->mFileFormater($migration)["file"]."\n";
        }

        $this->runMigrationAlters($alters);
    }

    public function runMigrationAlters($alters)
    {
        foreach($alters as $alter)
        {
            if(count($alter) > 0)
            {
                foreach($alter as $query)
                {
                    $schema = new Schema;
                    $schema->run($query);
                }
            }
        }
    }

    public function registerMigration($file, $version) 
    {
        $schema = new Schema;
        $schema->table = "migrations";
        $schema->insert(["migration" => $file, "version" => $version]);
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
        $ex = str_replace(".php", "", $split[2]);

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
}