<?php 

namespace App\Core\Database;

use ErrorException, PDO;

class Connection extends QueryBuilder {

    private $driver = "mysql";
    private $host;
    private $username;
    private $password;
    private $dbname;
    
    public function __construct()
    {
        $this->getConnectionVariable();
        $this->connect();
    }

    public function connect()
    {
        $this->buildConnectionString();
        $this->connection = new PDO($this->dataSource, $this->username, $this->password);

        // Set all attributes
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function buildConnectionString() 
    {
        $this->dataSource = $this->driver.":host=".$this->host.";dbname=".$this->dbname;
    }

    public function checkDatabaseVariables(array $variables, object $dbConnection)
    {
        foreach($variables as $variable) {
            if(!isset($dbConnection->$variable)){
                throw new ErrorException($variable." not found in database connection.");
            }
        }
    }

    public function getConnectionVariable()
    {
        $app_config = json_decode(file_get_contents("appsettings.json"));


        $this->checkDatabaseVariables(
            ["host", "username", "password", "database"], 
            $app_config->databaseConnection
        );

        
        $this->host = $app_config->databaseConnection->host;
        $this->username = $app_config->databaseConnection->username;
        $this->password = $app_config->databaseConnection->password;
        $this->dbname = $app_config->databaseConnection->database;
    }

    public function useDriver($driver) 
    {
        $this->driver = $driver;
    }

}