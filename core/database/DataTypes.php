<?php

namespace App\Core\Database;


class DataTypes {

    public $query;

    public $nullable = "NOT NULL";

    public function trimmer($str) 
    {
        return trim($str, ",");
    }

    public function bigIncrements() 
    {
        $this->query = "INT(16) AUTO_INCREMENT,";
        return $this;
    }

    public function boolean() 
    {
        $this->query = "TINYINT(1),";
        return $this;
    }

    public function date() 
    {
        $this->query = "DATE DEFAULT CURRENT_TIMESTAMP(),";
    }

    public function float($length = 10, $decimal = 2) 
    {
        $this->query = "FLOAT(". (string) $length.", ". (string) $decimal."),";
        return $this;
    }

    public function increments() 
    {
        $this->query = "INT(9) AUTO_INCREMENT,";
        return $this;
    }

    public function id($type = "int", $length = 6) 
    {

        switch($type) {
            case "int": 
                $this->query = "INT(". (string) $length .") UNIQUE,";
            break;
            case "string":
                $this->query = "VARCHAR(". (string) $length .") UNIQUE,";
            break;
        }

        return $this;
    }

    public function int($length = 9) 
    {
        $this->query = "INT(". (string) $length ."),";
        return $this;
    }

    public function integer($length = 9) 
    {
        $this->query = "INT(". (string) $length ."),";
        return $this;
    }

    public function string($length = 100) 
    {
        $this->query = "VARCHAR(". (string) $length ."),";
        return $this;
    }

    public function text() 
    {
        $this->query = "TEXT,";
        return $this;
    }

    public function longtext() 
    {
        $this->query = "LONGTEXT,";
        return $this;
    }

    public function null() 
    {

        $this->query = $this->trimmer($this->query);
        $this->query .= " DEFAULT NULL,";
        return $this;
    }

    public function time() 
    {
        $this->query = "TIME DEFAULT CURRENT_TIMESTAMP(),";
    }

    public function timestamp() 
    {
        $this->query = "DATETIME DEFAULT CURRENT_TIMESTAMP(),";
    }

    public function unique() 
    {
        $this->query = $this->trimmer($this->query);
        $this->query .= " UNIQUE,";
        return $this;
    }



}