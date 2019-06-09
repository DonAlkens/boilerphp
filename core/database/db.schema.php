<?php

namespace App\Core\Database;

use App\Config;
use mysqli;

class Schema {

    private $db;
    private $host; 
    private $port;
    private $user; 
    private $pass; 
    private $dbname;

    public function __construct() {
        include "./Config.php";

        $this->host = $dbConnection["HOST"];
        $this->port = $dbConnection["PORT"];
        $this->user = $dbConnection["USER"];
        $this->pass = $dbConnection["PASSWORD"];
        $this->dbname = $dbConnection["DBNAME"];


        $this->resultType = "";
        $this->queryMode = "";

        Schema::connect();
    }

    public function connect(){
        $this->db = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
    }

    public function table($name, $structure=null) {
        if($structure) {
            $this->table_map($name, $structure);
        }
        $this->table = $name;
        return $this;
    }

    public function insert($data) {
        if($data){
            $this->insert_map($data);
            return Schema::save();
        }
        return false;
    }

    public function all($props=null){
        $this->query = "select * from $this->table";
        if(!is_null($props)){
            if(array_key_exists("order",$props)) {
                $order = $props["order"]; 
                $key = $order["key"]; $mode = $order["mode"]; 
                $this->query .= " order by $key $mode";
            }
    
            if(array_key_exists("limit",$props)) {
                $limit = $props["limit"];
                $this->query .= " limit 0,$limit";
            }
        }
        
        $this->resultType = "multiple";
        return Schema::fetch();

    }

    public function select($params=null){
        $this->queryMode = "fetch";
        $this->select_map($params);
        return $this;
    }

    public function selectAll($params=null){
        $this->resultType = "multiple";
        return $this->select($params);
    }
    
    public  function update($data) {
        if(is_array($data)) {
            $this->queryMode = "run";
            $this->update_map($data);
        }
        return $this;
    }
    
    public function delete() {
        $this->queryMode = "run";
        $this->delete_map();
        return $this;
    }
    
    public function table_map($name, $structure){
        $i = 1; 
        $len = count($structure);
        $map = '(';

        foreach ($structure as $col => $type) {
            if($type == "sn") { $primary = true; $PCol = $col;}
            if($i == $len)
            {
                if(!array_key_exists('datetime',$structure)){
                    $map .= $col." ".$this->$type;
                    $map .= "created_date ".$this->datetime;
                    if(isset($primary)) {
                        $map .=", PRIMARY KEY(".$PCol.")";
                    }
                    break;
                } else {
                    $this->$type =  str_replace(",","",$this->$type);
                }
            }
            $map .= $col." ".$this->$type;
            $i++;
        }

        $map .= ')';
        $this->query = "create table $name".$map;
    }

    public function insert_map($data){
        $i = 1; 
        $len = count($data);

        $cols= '(';
        $values = '(';

        foreach ($data as $col => $value) {
            if($i == $len)
            {
                $cols .= $col;  
                $values .= "'".$value."'";
                break;
            }
            $cols .= $col.",";
            $values .= "'".$value."',";
            $i++;
        }
        $cols .= ')';
        $values .= ')';

        $this->query = "insert into $this->table".$cols." values".$values;
    }

    
    public function select_map($params){

        $map = "*";

        if($params !== null){
            $i = 1; 
            $len = count($params);
            $map = '';
    
            foreach ($params as $col => $value) {
                if($i == $len) { $map .= $value; break; }
                $map .= $value.",";
                $i++;
            }
        }

        $this->query = "select $map from $this->table where ";
    }


    public function where($params){
        $i = 1; 
        $len = count($params);
        $map = '';
        foreach ($params as $col => $value) {
            if($i == $len) { $map .= $col." = "."'".$value."'"; break; }
            $map .= $col." = "."'".$value."' and ";
            $i++;
        }

        $this->query .= $map;
        $mode = $this->queryMode;
        return Schema::$mode();
    }

    public function order() {

    }

    public function limit(){
        
    }

    public function update_map($data) {
        $i = 1; $len = count($data); $map = '';
        foreach ($data as $col => $value) {
            if($i == $len) {
                $map .= $col." = '".$value."' "; break;
            }  
            $map .= $col." = '".$value."', ";
            $i++;
        }

        $this->query = "update $this->table set ".$map." where ";
    }


    public function delete_map() {
        $this->query = "delete from $this->table where ";
    }

    public function run() {
        if(!empty($this->query)) {
            if($this->db->query($this->query)) {
                return true;
            }
            return false;
        }
    }

    public function save() {
        if($this->db != null) {
            if($this->db->query($this->query)) {
                return true;
            }
            return false;
        }
    }

    public function fetch(){
        $data = $this->db->query($this->query);
        if($this->resultType === "multiple"){
            if($data->num_rows > 0) {
                $result = [];
                while($row = $data->fetch_assoc()) {
                    array_push($result,$row);
    
                }
                return $result;
            }
        }
        else {
            if($data->num_rows > 0) {
                return $data->fetch_assoc();
            }
        }
        return null;
    }

    private $sn = "int(9) auto_increment not null,";
    private $id = "int(9) not null unique,";
    private $textid = "varchar(255) not null unique,";
    private $email = "varchar(255) not null unique,";
    private $text = "text not null,";
    private $longtext = "longtext not null,";
    private $int = "int(10) not null,";
    private $bool = "tinyint not null,";
    private $boolean = "tinyint not null,";
    private $string = "varchar(255) not null,";
    private $uniquestring = "varchar(255) not null unique,";
    private $datetime = "datetime default current_timestamp"; 

}