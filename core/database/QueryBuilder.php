<?php

namespace App\Core\Database;



class QueryBuilder extends DataTypes {

    public $columns;
    public $params;

    public function cleanQueryStrings() 
    {
        $this->columns = trim($this->columns, ", ");
        $this->params = trim($this->params, ", ");

    }

    public function insertQuery($data)
    {

        foreach ($data as $column => $value) {
            $this->columns .= "$column, ";
            $this->params .= ":$column, ";
        }

        $this->cleanQueryStrings();

        $this->queryString = "INSERT INTO $this->table ($this->columns) VALUES($this->params)";
        return $this->queryString;
    }

    public function selectQuery($columns)
    {

        foreach ($columns as $column => $value) {
            $this->columns .= "$column = :$column, ";
        }

        $this->cleanQueryStrings();

        $this->queryString = "SELECT * FROM $this->table WHERE $this->columns";
        return $this->queryString;
    }

    public function updateQuery($data)
    {
        foreach ($data as $column => $value) {
            $this->columns .= "$column = :$column, ";
        }

        $this->cleanQueryStrings();

        $this->queryString = "UPDATE $this->table SET $this->columns WHERE ";
        return $this->queryString;
    }


    public function deleteQuery()
    {
        $this->queryString = "DELETE FROM $this->table WHERE ";
        return $this->queryString;
    }

    public function dataFormatChecker($data, $value) {

        if(gettype($data) == "string") {
            if(!is_null($value)) {
                return $this->data = array($data => $value);
            } else {
                // $this->valueIsNullException();
            }
        }
        return $this->data = $data;
    }

    public function orderQuery($key, $order) 
    {
        return " ORDER BY $key $order";
    }

    public function resultTypeChecker($result) {
        return gettype($result);
    }

}