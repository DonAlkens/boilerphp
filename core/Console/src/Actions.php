<?php 

namespace Console\Support;

class Actions {

    /**
     * Create Controllers using command line manager
     * @param $name, $type
     * Boolean response if controller is created
    * */
    public function controller($name)
    {
        if(file_exists("../../controllers/".$name.".php")){
            echo "Controller already exists";
            return false;
        }

        // Configuring the controller components for new controller
        $readComponnent = file_get_contents("lib/components/controller.component");
        $Configure = preg_replace("/\[Controller\]/",$name, $readComponnent);
        $viewFolder = str_replace("Controller","",$name);
        $Configure = preg_replace("/\[View\]/",$viewFolder, $Configure);

        // Creating Controller File with default configurations
        $controller = fopen("../../controllers/".$name.".php","w");
        fwrite($controller, $Configure);
        fclose($controller);
    }


    public function model($name)
    {
        if(file_exists("../../models/".$name.".php")){
            echo "Model $name already exists";
            return false;
        }

        // Configuring the controller components for new controller
        $readComponnent = file_get_contents("lib/components/model.component");
        $Configure = preg_replace("/\[Model\]/",$name, $readComponnent);

        // Creating Model File with default configurations
        $model = fopen("../../models/".$name.".php","w");
        fwrite($model, $Configure);
        fclose($model);
    }

}