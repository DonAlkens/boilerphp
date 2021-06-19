<?php

namespace App\Core\Database;



class QueryBuilder extends DataTypes
{

	public $whereQuery = "";
	

	public function cleanQueryStrings()
	{
		isset($this->columns) ? $this->columns = trim($this->columns, ", ") : null;
		isset($this->params) ? $this->params = trim($this->params, ", ") : null;
	}

	public function allQuery()
	{
		$this->queryString = "SELECT * FROM $this->table ";
	}

	public function insertQuery($data)
	{
		$this->columns = "";
		$this->params = "";

		foreach ($data as $column => $value) {
			$this->columns .= "`$column`, ";
			$this->params .= ":$column, ";
		}

		$this->cleanQueryStrings();

		$this->queryString = "INSERT INTO $this->table ($this->columns) VALUES($this->params)";
		return $this->queryString;
	}

	public function selectQuery($fields)
	{

		$this->queryString = "SELECT $fields FROM $this->table ";
		return $this->queryString;
	}

	public function updateQuery($data)
	{
		$this->columns = "";
		foreach ($data as $column => $value) 
		{
			$this->columns .= "`$column` = :$column, ";
		}

		$this->cleanQueryStrings();

		$this->queryString = "UPDATE $this->table SET $this->columns ";
		if(isset($this->whereData))
		{
			$this->whereData = array_merge($data, $this->whereData);
		}
		else 
		{
			$this->whereData = $data;
		}
		return $this->queryString;
	}


	public function deleteQuery($data)
	{
		$this->columns = "";

		foreach ($data as $column => $value) 
		{
			$this->columns .= "`$column` = :$column, ";
		}

		$this->cleanQueryStrings();

		$this->queryString = "DELETE FROM $this->table WHERE $this->columns";
		$this->whereData = $data;
		return $this->queryString;
	}

	public function searchQuery($key, $value, $operation)
	{

		if (is_array($key)) 
		{
			if($this->whereQuery == "")
			{
				$this->whereQuery = " WHERE ";
			}
			else 
			{
				$this->whereQuery .= " AND ";
			}

			$search = " ( ";
			foreach ($key as $column => $val) 
			{
				$val = $operation[0].$val.$operation[1];
				$search .= "`$column` LIKE '$val' OR ";
			}

			$search =  trim($search, "OR ");
			$search .= " ) ";
			$this->whereQuery .= $search;
		} 
		
		else if (!is_array($key) && $value != null) 
		{
			$value = $operation[0].$value.$operation[1];
			if($this->whereQuery == "")
			{
				$this->whereQuery = " WHERE `$key` LIKE '$value'";
			}
			else 
			{
				$this->whereQuery .= " AND `$key` LIKE '$value'";
			}
		}

	}

	public function whereQuery($key, $value, $operation = null)
	{

		if (is_array($key)) 
		{
			$this->whereQuery = " WHERE ";

			$index = 0;
			foreach ($key as $column => $val) 
			{
				if($operation != null) 
				{
					if(is_array($operation)){
						$op = $operation[$index];
						$this->whereQuery .= "`$column` $op :$column AND ";
					}
					else
					{
						$this->whereQuery .= "`$column` $operation :$column AND ";
					}
				}
				else 
				{
					$this->whereQuery .= "`$column` = :$column AND ";
				}

				$index++;
			}

			$this->whereQuery = trim($this->whereQuery, "AND ");
			$this->whereData = $key;
		} 
		
		else if (!is_array($key) && $value != null) 
		{
			if($operation != null) 
			{
				$this->whereQuery = " WHERE `$key` $operation :$key";
			}
			else 
			{
				$this->whereQuery = " WHERE `$key` = :$key";
			}
			$this->whereData = array($key => $value);
		}

	}

	public function groupQuery($column)
	{
		$this->groupQuery = " GROUP BY `$column`";
	}

	public function orderQuery($key, $order, $limit)
	{
		$this->orderQuery = " ORDER BY `$key` $order";
		if($limit != null) {
			$this->orderQuery .= " LIMIT ".$limit;
		}
	}

	public function queryString()
	{
		if (!empty($this->queryString)) 
		{

			if(isset($this->whereQuery)) 
			{
				$this->queryString .= $this->whereQuery;
			}
			
			if (isset($this->groupQuery)) 
			{
				$this->queryString .= $this->groupQuery;
			}
			
			if (isset($this->orderQuery)) 
			{
				$this->queryString .= $this->orderQuery;
			}


			return $this->queryString;
		}

		return null;
	}

	public function dataFormatChecker($data, $value)
	{

		if (gettype($data) == "string") 
		{
			if (!is_null($value)) 
			{
				return $this->data = array($data => $value);
			} 
			else 
			{
				// $this->valueIsNullException();
			}
		}

		return $this->data = $data;
	}

	public function fieldFormatChecker($fields)
	{
		if (is_null($fields)) 
		{
			$fields = "*";
		}

		return $this->fields = $fields;
	}

	public function resultTypeChecker($result)
	{
		return gettype($result);
	}


	public function dropDatabaseTableQuery($table) {
		return "SET FOREIGN_KEY_CHECKS = 1; DROP TABLE IF EXISTS `$table`; SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS `$table`;";
	}
}
