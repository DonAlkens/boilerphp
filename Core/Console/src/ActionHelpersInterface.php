<?php

namespace Console\Support\Interfaces;

interface ActionHelpersInterface {

    /**
     * Confirguring notification structure and initialize new notification 
     * @param notification_name 
     * @param notification_path
     */

    public function configureNotification($notification_name, $notification_path);

    /**
     * Confirguring model structure and initialize new model 
     * @param model_name 
     * @param model_path
     */

    public function configureModel($model_name, $model_path);

    /**
     * Confirguring migration structure and initialize new migration  
     * @param migration_name
     * @param migration_path
     */

    public function configureMigration($migration_name, $migration_path);

    /**
     * Confirguring controller structure and initialize new controller 
     * @param controller_name
     * @param controller_path
     */

    public function configureController($controller_name, $controller_path);

    /**
     * checks and returns command length
     * @param command 
     * @return int
     */

    public function getCommandLength(array $command);

    /**
     * reads the component file and get the components structure
     * @param component_file_path
     * @return component
     */

    public function readComponent($path);

     /**
     * handles flag and create mocules
     * @param flag name action
     * @return void
     */

     public function flagHandler($flag, $name, $action);


     /**
     * check available all migrations
     * @return bool
     */

    public function newMigrationsChecker();


    /**
     * drops all the existing tables in the database
     * @return void
     */
    public function dropAllExistingTable();


     /**
     * require/include file 
     * @param string filepath
     * @return void
     */

    public function requireOnce($filepath);    
}