<?php

namespace App\Core\Database;



class QueryBuilder extends DataTypes
{


	public function cleanQueryStrings()
	{
		isset($this->columns) ? $this->columns = trim($this->columns, ", ") : null;
		isset($this->params) ? $this->params = trim($this->params, ", ") : null;
	}

	public function allQuery()
	{
		$this->queryString = "SELECT * FROM $this->table";
	}

	public function insertQuery($data)
	{
		$this->columns = "";
		$this->params = "";

		foreach ($data as $column => $value) {
			$this->columns .= "$column, ";
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
			$this->columns .= "$column = :$column, ";
		}

		$this->cleanQueryStrings();

		$this->queryString = "UPDATE $this->table SET $this->columns ";
		$this->whereData = array_merge($data, $this->whereData);
		return $this->queryString;
	}


	public function deleteQuery($data)
	{
		$this->columns = "";

		foreach ($data as $column => $value) 
		{
			$this->columns .= "$column = :$column, ";
		}

		$this->cleanQueryStrings();

		$this->queryString = "DELETE FROM $this->table WHERE $this->columns";
		$this->whereData = $data;
		return $this->queryString;
	}

	public function whereQuery($key, $value, $operation = null)
	{

		if (is_array($key)) 
		{
			$this->whereQuery = " WHERE ";

			foreach ($key as $column => $val) 
			{
				if($operation != null) 
				{
					$this->whereQuery = "$column $operation :$column AND ";
				}
				else 
				{
					$this->whereQuery .= "$column = :$column AND ";
				}
			}

			$this->whereQuery = trim($this->whereQuery, "AND ");
			$this->whereData = $key;
		} 
		
		else if (!is_array($key) && $value != null) 

		{
			if($operation != null) {
				$this->whereQuery = " WHERE $key $operation :$key";
			}
			else 
			{
				$this->whereQuery = " WHERE $key = :$key";
			}
			$this->whereData = array($key => $value);
		}

	}

	public function groupQuery($column)
	{
		$this->groupQuery = " GROUP BY $column";
	}

	public function orderQuery($key, $order)
	{
		$this->orderQuery = " ORDER BY $key $order";
	}

	public function queryString()
	{
		if (!empty($this->queryString)) 
		{

			if(isset($this->whereQuery)) 
			{
				$this->queryString .= $this->whereQuery;
			}
			
			if (isset($this->orderQuery)) 
			{
				$this->queryString .= $this->orderQuery;
			}

			if (isset($this->groupQuery)) 
			{
				$this->queryString .= $this->groupQuery;
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
}
