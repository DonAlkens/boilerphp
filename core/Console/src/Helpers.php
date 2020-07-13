<?php 

namespace Console\Support;

class Helpers {

    public $commands = array(
        "create", "start"
    );

    public $flags = array(
        "--m" => "model",
        "--a" => "all",
        "--c" => "controller",
        "--d" => "migration"
    );

    public function flag_checker($action, $flag) {
        if($flag != null) {
            if($this->flags[$flag] == $action) {
                echo "mis-usage of flag on create ". $action;
                return false;
            }

            return true;
        }

        return false;
    }

    public function check_existent($path) {
        if(file_exists($path)){
            return true;
        }
        return false;
    }


    public function configure_model($name) 
    {
        $this->module = preg_replace("/\[Model\]/",$name, $this->component);
    }


    public function configure_migration($name) 
    {
        $this->module = preg_replace("/\[MigrationName\]/",$name."Migration", $this->component);
        $this->module = preg_replace("/\[TableName\]/", strtolower($name."s"), $this->module);
    }


    public function configure_controller($name) 
    {
        $this->module = preg_replace("/\[Controller\]/", $name, $this->component);

        $view_folder = str_replace("controller", "", strtolower($name));
        
        if(!$this->check_existent("./Views/".$view_folder)) {
            mkdir("./Views/".$view_folder);
        }

        $this->module = preg_replace("/\[View\]/", $view_folder, $this->module);
    }

    
    public function length($command) {
        return count($command);
    }


    public function read_model_component() 
    {
        $this->component = file_get_contents("./Core/Console/lib/components/model.component");
    }


    public function read_migration_component() 
    {
        $this->component = file_get_contents("./Core/Console/lib/components/migration.component");
    }

    public function read_controller_component() 
    {
        $this->component = file_get_contents("./Core/Console/lib/components/controller.component");
    }

    public function write_module($path)
    {
        $module = fopen($path, "w");
        fwrite($module, $this->module);
        fclose($module);
    }

}