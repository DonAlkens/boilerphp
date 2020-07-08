<?php

namespace App\Core\Database;

use mysqli;

class Schema extends QueryBuilder
{
    private $db;
    private $host;
    private $port;
    private $user;
    private $pass;
    private $dbname;
    
    public function __construct()
    {
        
        include "./config.php";
        
        $this->host = $dbConnection["HOST"];
        $this->port = $dbConnection["PORT"];
        $this->user = $dbConnection["USER"];
        $this->pass = $dbConnection["PASSWORD"];
        $this->dbname = $dbConnection["DBNAME"];

        $this->connect();
    }

    public function connect()
    {
        $this->db = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
    }

    public function table($name, $structure=null)
    {
        if ($structure) {
            $this->table_map($name, $structure);
        }
        
        $this->table = $name;
        return $this;
    }

    public function insert($data)
    {
        if ($data) {

            foreach ($data as $key => $value) {
                # code...
                $this->$key = $value;
            }

            $this->insert_map($data);
            if ($this->db != null) {
                if($this->db->query($this->query)) {

                    $this->new_obj_item_g = $this->select()->where($data);
                    foreach ($this->new_obj_item_g as $key => $value) {
                        # code...
                        $this->$key = $value;
                    }
        
                    return true;
                }
            }

        }
        return false;
    }

    public function select($params=null)
    {
        $this->queryMode = "fetch";
        $this->select_map($params);
        return $this;
    }

    public function selectAll($params=null)
    {
        $this->resultType = "multiple";
        return $this->select($params);
    }
    
    public function update($data)
    {
        if (is_array($data)) {
            $this->queryMode = "run";
            $this->update_map($data);
        }
        return $this;
    }
    
    public function delete()
    {
        $this->queryMode = "run";
        $this->delete_map();
        return $this;
    }

    public function order()
    {
    }

    public function limit()
    {
    }

    public function run()
    {
        if (!empty($this->query)) {
            if ($this->db->query($this->query)) {
                return true;
            }
            return false;
        }
    }

    public function save()
    {
        if ($this->db != null) {
            if ($this->db->query($this->query)) {
                return true;
            }
            return false;
        }
    }

    public function fetch()
    {
        $data = $this->db->query($this->query);
        if ($this->resultType == "multiple") {
            if ($data->num_rows > 0) {
                $result = [];
                while ($row = $data->fetch_assoc()) {

                    $Class = get_class($this);
                    $object = new $Class;

                    foreach ($row as $key => $value) {
                        # code...
                        $object->$key = $value;
                    }

                    array_push($result, $object);
                }
                
                return $result;
            }
        } else if($this->resultType != "multiple") {
            if ($data->num_rows > 0) {
                $row = $data->fetch_assoc();
                
                // $Class = get_class($this);
                // $object = new $Class;

                foreach ($row as $key => $value) {
                    # code...
                    $this->$key = $value;
                }

                return $this;
            }
        }
        return null;
    }
    
    public function query($querystring)
    {
        if ($querystring !== "") {
            $data = $this->db->query($querystring);
            return $data->fetch_assoc();
        }
        return null;
    }

    private $int = "int(10) default null,";
    private $integer = "int(10) default null,";
    private $uniqueInteger = "int(9) not null unique,";
    private $bigUniqueInteger = "int(16) not null unique,";
    private $increments = "int(9) auto_increment not null,";
    private $bigIncrements = "int(16) auto_increment not null,";
    private $text = "text default null,";
    private $longText = "longtext default null,";
    private $uniqueText = "text not null unique,";
    private $string = "varchar(255) default null,";
    private $uniqueString = "varchar(255) not null unique,";
    private $float = "float(10,7) default null,";
    private $floatL1 = "float(10,1) default null,";
    private $floatL2 = "float(10,2) default null,";
    private $floatL3 = "float(10,3) default null,";
    private $floatL4 = "float(10,4) default null,";
    private $floatL5 = "float(10,5) default null,";
    private $floatL6 = "float(10,6) default null,";
    private $floatL7 = "float(10,7) default null,";
    private $bool = "tinyint(1) default 0,";
    private $boolean = "tinyint(1) default 0,";
    private $datetime = "datetime default current_timestamp";
}

// fetchAll();
// first([$key], [$value])
// last([$key], [$value])
// where([$key], [$value])
// orderBy([$key], [$value])
// get()
