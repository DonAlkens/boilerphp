<?php 

namespace App\Core\Database;

use ErrorException, PDO;

class Connection extends QueryBuilder 
{

    /**
    * database driver
    *
    * @var string
    *
    */
    private $driver = "mysql";


    /**
    * database hostname
    *
    * @var string
    *
    */
    private $host;


    /**
    * database username
    *
    * @var string
    *
    */
    private $username;


    /**
    * database password
    *
    * @var string
    *
    */
    private $password;


    /**
    * database name
    *
    * @var string
    *
    */
    private $dbname;

    /**
    * database connection props index
    *   
    * @var integer
    *
    */
    private $dbConnection;
    
    
    public function __construct()
    {
        $this->getConnectionVariable();
    }

    public function db($name = null)
    {
        if(!is_null($name)) {
            $this->dbConnection = $name;
        }

        $this->getConnectionVariable();
        $this->connect();

        return $this;
    }

    public function connect()
    {
        if(!isset($this->connection)) {
            $this->buildConnectionString();
    
            try {
    
                $this->connection = new PDO($this->dataSource, $this->username, $this->password);
        
                // Set all attributes
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $this->connection->setAttribute(PDO::ATTR_PERSISTENT, true);
                $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
                $this->connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    
            } catch (\PDOException $pd) {
                throw $pd; exit;
                echo "Error Occured: ".$pd->message; exit;
            }
        }

    }

    protected function buildConnectionString() 
    {
        $this->dataSource = $this->driver.":host=".$this->host.";dbname=".$this->dbname;
    }

    protected function checkDatabaseVariables(array $variables, object $dbConnectionVariables)
    {
        foreach($variables as $variable) {
            if(!isset($dbConnectionVariables->$variable)){
                throw new ErrorException($variable." not found in database connection.");
            }
        }
    }

    protected function getConnectionVariable()
    {
        $app_config = json_decode(file_get_contents("appsettings.json"));

        if($this->getDbSelection($app_config)) {
            $app_config->databaseConnection = $this->selectedConnectionVariables;
        } else {
            $app_config->databaseConnection = $app_config->databaseConnections->default;
        }

        $this->checkDatabaseVariables(
            ["host", "username", "password", "database"], 
            $app_config->databaseConnection
        );

        
        $this->host = $app_config->databaseConnection->host;
        $this->username = $app_config->databaseConnection->username;
        $this->password = $app_config->databaseConnection->password;
        $this->dbname = $app_config->databaseConnection->database;
    }

    protected function getDbSelection($app_config) {

        if($this->dbConnection != null) 
        {
            # ch3ck if database connection name exists
            $n = $this->dbConnection;
            if(!isset($app_config->databaseConnections->$n)) {
                # throw undefined connection name error;
                exit;
            }

            $this->selectedConnectionVariables = $app_config->databaseConnections->$n;
            return true;
        }

        return false;
    }

    protected function useDriver($driver) 
    {
        $this->driver = $driver;
    }

    public function getDbName()
    {
        return $this->dbname;
    }

    public function __sleep() {
        return ["connection"]; //Pass the names of the variables that should be serialised here
   }
   
   public function __wakeup() {
        //Since we can't serialize the connection we need to re-open it when we unserialise
        $this->getConnectionVariable();
        $this->connect();
   }
}