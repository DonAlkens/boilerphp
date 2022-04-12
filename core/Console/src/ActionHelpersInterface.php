<?php

namespace Console\Support\Interfaces;

interface ActionHelpersInterface {

    /**
     * Confirguring notification structure and initialize new notification 
     * @param string $notification_name 
     * 
     * @param string $notification_path
     */

    public function configureNotification($notification_name, $notification_path);

    /**
     * Confirguring model structure and initialize new model 
     * @param string $model_name 
     * 
     * @param string model_path
     */

    public function configureModel($model_name, $model_path);

    /**
     * Confirguring migration structure and initialize new migration  
     * @param string migration_name
     * 
     * @param string migration_path
     * 
     * @param string component
     */

    public function configureMigration($migration_name, $migration_path, $component);

    /**
     * Confirguring controller structure and initialize new controller 
     * @param string $controller_name
     * 
     * @param string $controller_path
     */

    public function configureController($controller_name, $controller_path);

    /**
     * Confirguring seeder structure and initialize new seeder 
     * @param string $seeder_name
     * 
     * @param string $seeder_path
     */
    public function configureSeeder($seeder_name, $seeder_path);

    /**
     * checks and returns command length
     * @param array $command 
     * 
     * @return int
     */

    public function getCommandLength(array $command);

    /**
     * reads the component file and get the components structure
     * @param $_path
     * 
     * @return component
     */

    public function readComponent($path);

     /**
     * handles flag and create mocules
     * @param $flag 
     * 
     * @param $name 
     * 
     * @param action
     * 
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
     * @param string $filepath
     * 
     * @return void
     */

    public function requireOnce($filepath);    
}