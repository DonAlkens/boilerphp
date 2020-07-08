<?php

namespace App\Core\Database;



class QueryBuilder extends DataTypes {

    public function __construct()
    {
        $this->resultType = "";
    }

    public function all($props=null)
    {
        $this->query = "SELECT * FROM $this->table";
        if (!is_null($props)) {
            if (array_key_exists("order", $props)) {
                $order = $props["order"];
                $key = $order["key"];
                $mode = $order["mode"];
                $this->query .= " ORDER BY $key $mode";
            }
    
            if (array_key_exists("limit", $props)) {
                $limit = $props["limit"];
                $this->query .= " LIMIT 0,$limit";
            }
        }
        
        $this->resultType = "multiple";
        return $this->query;
    }

    public function table_map($name, $structure)
    {
        $i = 1;
        $len = count($structure);
        $map = '(';

        foreach ($structure as $col => $type) {
            if ($type == "increments" || $type == "bigIncrements") {
                $primary = true;
                $PCol = $col;
            }
            if ($i == $len) {
                if (!array_key_exists('datetime', $structure)) {
                    $map .= $col." ".$this->$type;
                    $map .= "`created_date` ".$this->datetime;
                    if (isset($primary)) {
                        $map .=", PRIMARY KEY(".$PCol.")";
                    }
                    break;
                } else {
                    $this->$type =  str_replace(",", "", $this->$type);
                }
            }
            $map .= $col." ".$this->$type;
            $i++;
        }

        $map .= ')';
        $this->query = "CREATE TABLE $name".$map;
        return $this->query;
    }

    public function insert_map($data)
    {
        $i = 1;
        $len = count($data);

        $cols= '(';
        $values = '(';

        foreach ($data as $col => $value) {
            if ($i == $len) {
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

        $this->query = "INSERT INTO $this->table".$cols." VALUES".$values;
    }

    public function select_map($params)
    {
        $map = "*";

        if ($params !== null) {
            $i = 1;
            $len = count($params);
            $map = '';
    
            foreach ($params as $col => $value) {
                if ($i == $len) {
                    $map .= $value;
                    break;
                }
                $map .= $value.",";
                $i++;
            }
        }

        $this->query = "SELECT $map FROM $this->table WHERE ";
        return $this->query;
    }

    public function update_map($data)
    {
        $i = 1;
        $len = count($data);
        $map = '';
        foreach ($data as $col => $value) {
            if ($i == $len) {
                $map .= $col." = '".$value."' ";
                break;
            }
            $map .= $col." = '".$value."', ";
            $i++;
        }

        $this->query = "UPDATE $this->table SET ".$map." WHERE ";
        return $this->query;
    }


    public function delete_map()
    {
        $this->query = "DELETE FROM $this->table WHERE ";
        return $this->query;
    }

    public function where($params)
    {
        $i = 1;
        $len = count($params);
        $map = '';
        foreach ($params as $col => $value) {
            if ($i == $len) {
                $map .= $col." = "."'".$value."'";
                break;
            }
            $map .= $col." = "."'".$value."' and ";
            $i++;
        }

        $this->query .= $map;
        $mode = $this->queryMode;
        return Schema::$mode();
    }
}