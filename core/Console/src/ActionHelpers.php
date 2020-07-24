<?php 

namespace Console\Support;

use Console\Support\Interfaces\ActionHelpersInterface;



class ActionHelpers implements ActionHelpersInterface {

    public $commands = array(
        "create", "start"
    );

    public $flags = array(
        "--m" => "model",
        "--a" => "all",
        "--c" => "controller",
        "--d" => "migration"
    );

    public $configurations = array(
        "model" => "configiureModel",
        "controller" => "configureController",
        "migration" => "configureMigration",
        "notification" => "configureNotification"
    );

    public $paths = array(
        "model" => "./Models/",
        "controller" => "./Controllers/",
        "migration" => "./Migrations/",
    );

    /**
     * checks flag and action
     * for difference
     * @return bool
     */

    public function flagchecker($action, $flag) 
    {
        
        if($this->flags[$flag] == $action) {
            echo "mis-usage of flag on create ". $action;
            return false;
        }

        return true;
    }

    public function flagHandler($name, $flag, $action) 
    {

        if($flag == "--a") {
            foreach($this->flags as $flag => $task) {
                if($task == $action || $task == "all") {
                    continue;
                }

                $this->flagConfig($flag, $name);
            }
        } else {
            $this->flagConfig($flag, $name);
        }

    }

    public function flagConfig($flag, $name) 
    {
        $task = $this->flags[$flag];

        if($task == "controller") { $name .= "Controller"; }

        $path = $this->paths[$task].$name.".php";
        $configuration = $this->configurations[$task];
        $this->$configuration($name, $path);

    }

    public function checkExistent($path) {

        if(file_exists($path)){ return true;}
        return false;
    }

    /**
     * usage: configures model structure and inital setup
     * @param model_name
     * @param model_path
     */

    public function configureModel($model_name, $model_path) 
    {
        $component_path = "./Core/Console/lib/components/model.component";

        if($this->readComponent($component_path)) {
            $this->module = preg_replace("/\[Model\]/",$model_name, $this->component);
            if($this->writeModule($model_path)) {
                echo "Model $model_name successfully created!\n";
                return true;
            }
            return false;
        }
    }

    
    /**
     * usage: configures migration structure and inital setup
     * @param migration_name
     * @param migration_path
     */
    public function configureMigration($migration_name, $migration_path) 
    {
        $component_path = "./Core/Console/lib/components/migration.component";
        if($this->readComponent($component_path)) {
            $this->module = preg_replace("/\[MigrationName\]/",$migration_name."Migration", $this->component);
            $this->module = preg_replace("/\[TableName\]/", strtolower($migration_name."s"), $this->module);

            if($this->writeModule($migration_path)) {
                echo "Migration $migration_name successfully created!\n";
                return true;
            }
            return false;
        }
    }


    /**
     * usage: configures controller structure and inital setup
     * @param controller_name
     */
    public function configureController($controller_name, $controller_path) 
    {
        $component_path = "./Core/Console/lib/components/controller.component";

        if($this->readComponent($component_path) !== "") {

            $this->module = preg_replace("/\[Controller\]/", $controller_name, $this->component);
            $view_folder = str_replace("controller", "", strtolower($controller_name));

            if(!$this->checkExistent("./Views/".$view_folder)) {
                mkdir("./Views/".$view_folder);
            }
    
            $this->module = preg_replace("/\[View\]/", $view_folder, $this->module);
            if($this->writeModule($controller_path)) {
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
     * @param component_file_path
     * @return component
     */
    public function readComponent($path)
    {
        $this->component = file_get_contents($path); return $this->component;
    }



    public function writeModule($path)
    {
        $module = fopen($path, "w"); fwrite($module, $this->module); return fclose($module);
    }

}