<?php 

namespace Console\Support;

require_once __DIR__."/Helpers.php";

class Actions extends Helpers {

    /**
     * Create Controllers using command line manager
     * @param $name, $type
     * Boolean response if controller is created
    * */
    public function controller($name, $flag = null)
    {
        $this->path = "./Controllers/".$name.".php";

        if($this->check_existent($this->path)) {
            echo "$name already exists"; exit;
        }

        $this->read_controller_component();
        $this->configure_controller($name);
        $this->write_module($this->path);
        
        echo "$name successfully created!";
    }


    public function model($name, $flag = null)
    {
        if($this->flag_checker("model", $flag)) {
            // $this->flag_manager($name);
        } else {
            return false;
        }

        $this->path = "./Models/".$name.".php";

        if($this->check_existent($this->path)) {
            echo "Model $name already exists"; exit;
        }

        $this->read_model_component();
        $this->configure_model($name);
        $this->write_module($this->path);
        
        echo "Model $name successfully created!";
    }

    public function migration($name, $flag = null)
    {
        $this->path = "./Migrations/".$name."Migration.php";

        if($this->check_existent($this->path)) {
            echo "Migrations $name already exists"; exit;
        }

        $this->read_migration_component();
        $this->configure_migration($name);
        $this->write_module($this->path);
        
        echo "Migrations $name successfully created!";
    }

}