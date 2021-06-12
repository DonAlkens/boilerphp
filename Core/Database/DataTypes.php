<?php

namespace App\Core\Database;


class DataTypes {


    /**
    * App formatted query
    *
    * @var string
    *
    */

    public $query;


    /**
    * All alter querys from table contructions
    *
    * @var array
    *
    */

    public $alters = array();


    /**
    * foreign key query
    *
    * @var string
    *
    */

    public $foreignKey = "";
    

    /**
    * primary key query
    *
    * @var string
    *
    */
    public $primary_keys;



    /**
    * default nullable contraint on datatypes
    *
    * @var string
    *
    */
    public $nullable = "NOT NULL";


    public function trimmer($str) 
    {
        return trim($str, ",");
    }


    /**
     * Set column datatype to big increments
     * 
     * @return App\Core\Database\DataTypes
     */
    public function bigIncrements() 
    {
        $this->primary_keys .= " $this->column,";
        $this->query .= " $this->column BIGINT(20) AUTO_INCREMENT,";
        return $this;
    }


    /**
     * Set column datatype to big integer
     * 
     * @param $length 
     * 
     * @return App\Core\Database\DataTypes
     */
    public function bigInteger($length = 20) 
    {
        $this->query .= " $this->column BIGINT(". (string) $length ."),";
        return $this;
    }


    /**
     * Set column datatype to boolean
     * 
     * @return App\Core\Database\DataTypes
     */
    public function boolean() 
    {
        $this->query .= " $this->column TINYINT(1),";
        return $this;
    }

    public function cascade()
    {
        $this->foreignKey = $this->trimmer($this->foreignKey);
        $this->foreignKey .= "ON DELETE CASCADE ,";
        return $this;
    }


    /**
     * Set column datatype to date
     * 
     * @return App\Core\Database\DataTypes
     */
    public function date()
    {
        $this->query .= " $this->column DATE NOT NULL ,";
    }


    /**
     * Set column default value
     * 
     * @return App\Core\Database\DataTypes
     */
    public function default($value)
    {
        $this->query = $this->trimmer($this->query);
        $this->query .= " DEFAULT {$value},";
        return $this;
    }


    /**
     * Set column datatype to floating datatype
     * 
     * @param $length
     * @param $decimal 
     * 
     * @return App\Core\Database\DataTypes
     */
    public function float($length = 10, $decimal = 2) 
    {
        $this->query .= " $this->column FLOAT(". (string) $length.", ". (string) $decimal."),";
        return $this;
    }


    /**
     * Define a column as a foreign key column
     * and set the relationship keys.
     * 
     * @param $table - name of the foreign table
     * @param $reference - relating column of the foreign table
     * 
     * @return App\Core\Database\ColumnDefination
     */
    public function foreign($table, $reference = "id")
    {
        $this->primary_keys .= " $this->column,";
        $reference = is_null($reference) ? $this->column : $reference;

        $const = $table."_".$this->table."_".$this->column."_fk";
        $this->foreignKey .= " ADD CONSTRAINT `$const` FOREIGN KEY ($this->column) REFERENCES `$table` (`$reference`) ,";
        
        return $this;
    }


    public function foreignKeyProccessor($table)
    {
        if($this->foreignKey != "")
        {
            $query = $this->trimmer($this->foreignKey);
            $alter_query = "ALTER TABLE $table ".$query;
            array_push($this->alters, $alter_query);
        }
    }


    /**
     * Set column datatype to integer datatype
     * with auto increment value.
     * 
     * @return App\Core\Database\DataTypes
     */
    public function increments() 
    {
        $this->primary_keys .= " $this->column,";
        $this->query .= " $this->column INT(9) AUTO_INCREMENT,";
        return $this;
    }


    /**
     * Creates a column with name id and sets datatype to a 
     * big integer datatype with auto increment value.
     * 
     * @param $name - Default value 'id'
     * 
     * @return App\Core\Database\DataTypes
     */
    public function id($name = "id")
    {
        $this->column = $name;
        $this->primary_keys .= " $this->column,";
        $this->query .= " $this->column BIGINT(20) AUTO_INCREMENT,";
        return $this;
    }


    /**
     * Creates a unique column with name id and sets datatype to
     * big integer datatype with key and auto increment value. 
     * 
     * @param $name - Default value 'id'
     * 
     * @return App\Core\Database\DataTypes
     */
    public function uniqeId($name = "id")
    {
        $this->column = $name;
        $this->primary_keys .= " $this->column,";
        $this->query .= " $this->column BIGINT(20) NOT NULL UNIQUE,";
        return $this;
    }


    /**
     * Creates a unique column with varchar datatype. 
     * 
     * @param $length - Default value '100'
     * 
     * @return App\Core\Database\DataTypes
     */
    public function stringId($length = 100)
    {
        $this->primary_keys .= " $this->column,";
        $this->query .= " $this->column VARCHAR(". (string) $length .") UNIQUE,";
        return $this;
    }


    /**
     * Set column datatype to integer
     * 
     * @param $length - Default value '9'
     * 
     * @return App\Core\Database\DataTypes
     */
    public function integer($length = 9) 
    {
        $this->query .= " $this->column INT(". (string) $length ."),";
        return $this;
    }


    /**
     * Set column datatype to varchar
     * 
     * @param $length - Default value '100'
     * 
     * @return App\Core\Database\DataTypes
     */
    public function string($length = 100) 
    {
        $this->query .= " $this->column VARCHAR(". (string) $length ."),";
        return $this;
    }


    /**
     * Set column datatype to text
     * 
     * @return App\Core\Database\DataTypes
     */
    public function text() 
    {
        $this->query .= " $this->column TEXT,";
        return $this;
    }


    /**
     * Set column datatype to longtext
     * 
     * @return App\Core\Database\DataTypes
     */
    public function longtext() 
    {
        $this->query .= " $this->column LONGTEXT,";
        return $this;
    }

    /**
     * Define column as primary key
     * 
     * @return App\Core\Database\DataTypes
     */
    public function primary()
    {
        $this->primary_keys .= " $this->column,";
        return $this;
    }

    /**
     * Define default state of a column
     * If set 'true' value will be NULL
     * and if set 'false' value will be NOT NULL 
     * 
     * @param $state - Default value 'true'
     * 
     * @return App\Core\Database\DataTypes
     */
    public function nullable($state = true) 
    {

        $this->query = $this->trimmer($this->query);
        $this->query .= ($state) ? " DEFAULT NULL," : "NOT NULL";
        return $this;
    }

    /**
     * Set column datatype to time
     * 
     * @return App\Core\Database\DataTypes
     */
    public function time() 
    {
        $this->query .= " $this->column TIME DEFAULT CURRENT_TIMESTAMP(),";
    }


    /**
     * Set column datatype to datetime
     * 
     * @return App\Core\Database\DataTypes
     */
    public function timestamp() 
    {
        $this->query .= " $this->column DATETIME,";
        return $this;
    }

    public function unique() 
    {
        $this->query = $this->trimmer($this->query);
        $this->query .= " UNIQUE,";
        return $this;
    }


}