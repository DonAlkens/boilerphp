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

    public function fetchAll(){
        $this->query = "select * from $this->table";
        return Schema::sAll();
    }

    public function select($params, $order=null, $limit=null){
        if($params) {
            $this->select_map($params, $order, $limit);
            return Schema::fetch();
        } 
        return null;
    }

    public function selectAll($params, $order=null, $limit=null){
        if($params) {
            $this->select_map($params, $order, $limit);
            return Schema::sAll();
        } 
        return null;
    }
    
    public  function update($ref, $data) {
        if(!empty($ref) && is_array($data)) {
            $this->update_map($ref, $data);
            return Schema::run();
        }
        return false;
    }
    
    public function delete($ref) {
        if(is_array($ref) && !is_null($ref)){
            $this->delete_map($ref);
            return Schema::run();
        }
    }
    
    public function table_map($name, $structure){
        $i = 1; 
        $len = count($structure);
        $map = '(';

        foreach ($structure as $col => $type) {
            if($i == $len)
            {
                if(!array_key_exists('datetime',$structure)){
                    $map .= $col." ".$this->$type;
                    $map .= "created_date ".$this->datetime;
                    break;
                } else {
                    $this->$type =  str_replace(",","",$this->$type);
                }
            }
            $map .= $col." ".$this->$type;
            $i++;
        }

        if(array_key_exists("sn",$structure)){
            $map .= ", primary key(sn)";
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

    
    public function select_map($params, $order, $limit){
        $i = 1; 
        $len = count($params);
        $map = '';

        foreach ($params as $col => $value) {
            if($i == $len) { $map .= $col." = "."'".$value."'"; break; }
            $map .= $col." = "."'".$value."' and ";
            $i++;
        }

        if($order) {
            $key = $order["key"]; $mode = $order["mode"]; 
            $map .= "order by $key $mode";
        }

        $this->query = "select * from $this->table where ".$map;
    }

    public function update_map($ref, $data) {
        $i = 1; $len = count($data); $map = '';
        foreach ($data as $col => $value) {
            if($i == $len) {
                $map .= $col." = '".$value."' "; break;
            }  
            $map .= $col." = '".$value."', ";
            $i++;
        }

        $i = 1; $len = count($ref); $_ref = '';
        foreach($ref as $col => $value){
            if($i == $len) {
                $_ref .= $col." = '".$value."' "; break;
            }
            $_ref .= $col." = '".$value."' and ";
            $i++;
        }
        $this->query = "update $this->table set ".$map." where ".$_ref;
    }


    public function delete_map($ref) {
        $i = 1; $len = count($ref); $map = '';
        foreach ($ref as $col => $value) {
            if($i === $len){
                $map .=  $col." = '".$value."'";
                break;
            }
            $map .=  $col." = '".$value."' and ";
            $i++;
        }
        $this->query = "delete from $this->table where ".$map;
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
        if($data->num_rows == 1) {
            return $data->fetch_assoc();
        }
        return null;
    }

    public function sAll(){
        $data = $this->db->query($this->query);
        if($data->num_rows > 0) {
            $result = [];
            while($row = $data->fetch_assoc()) {
                array_push($result,$row);

            }
            return $result;
        }
        return null;
    }

    private $sn = "int auto_increment not null,";
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