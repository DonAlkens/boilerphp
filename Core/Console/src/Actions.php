<?php 

namespace Console\Support;



class Actions extends ActionHelpers {

    /**
     * Create Controllers using command line manager
     * @param $name, $type
     * Boolean response if controller is created
    * */
    public function controller($name, $flag = null)
    {
        $this->path = "./Controllers/".$name.".php";

        if($this->checkExistent($this->path)) {
            echo "$name already exists"; 
            return false;
        }

        if($this->configureController($name, $this->path)) {

            if(isset($this->run_flag) && $this->run_flag) {
                $this->flagHandler($name, $flag, "controller", $this->path);
            }

            return true;
        }

        print("Unable to create model ".$name);
        return false;
    }


    public function model($name, $flag = null)
    {
        if(!is_null($flag)) {
            if($this->flagChecker("model", $flag)) { $this->run_flag = true;} 
            else { return $this->run_flag = false; }
        }

        $path = "./Models/".$name.".php";

        if($this->checkExistent($path)) {
            echo "Model $name already exists"; exit;
        }

        if($this->configureModel($name, $path)) {

            if(isset($this->run_flag) && $this->run_flag) {
                $this->flagHandler($name, $flag, "model", $path);
            }

            return true;
        }
        
        print("Unable to create model ".$name);
        return false;
    }

    public function migration($name, $flag = null)
    {
        $file_name = strtolower($name)."_table.php";
        $this->path = "./Migrations/".time()."_".$file_name;

        if($this->checkMigrationExistent($file_name)) {
            echo "Migration $name already exists"; exit;
        }

        if($this->configureMigration($name, $this->path)) {

            if(isset($this->run_flag) && $this->run_flag) {
                $this->flagHandler($name, $flag, "migration", $this->path);
            }

            return true;
        }

        print("Unable to create model ".$name);
        return false;
    }

    public function migrate($flag = null)
    {
        $this->migrationFlagHandler($flag);
        if($this->newMigrationsChecker()) {
            if(!$this->checkTableExists("migrations")){
                $this->createMigrationsTable();
            }

            $this->runMigrations();

            return true;
        }

       echo "No new migrations";
    }
}