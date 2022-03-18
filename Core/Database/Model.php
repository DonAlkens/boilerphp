<?php

namespace App\Core\Database;



class Model extends Relations {

    

    public function __construct()
    {
        $this->useTable();
        parent::__construct();
    }
    
    protected function useTable()
    {
        if($this->table == null) 
        {
            $namespace = explode("\\", get_class($this));
            $namespace = str_split(end($namespace));

            $format = "";
            foreach($namespace as $key => $char) 
            {
                if(ctype_upper($char)) 
                {
                    $format .= "_".strtolower($char); 
                    continue;
                }

                $format .= $char;
            }

            $table = trim($format, "_");
            $lastchar = strtolower(substr($table, -1));
            
            if($lastchar == "y" && !in_array($table, $this->specialTableChars)) {
                $table = substr($table, 0, (strlen($table) - 1))."ies";
            }
            else if($lastchar == "x" && !in_array($table, $this->specialTableChars)) {
                $table .= "es";
            }
            else if($lastchar != "s" && !in_array($table, $this->specialTableChars)) {
                $table .= "s";
            }

            $this->table = $table;
        }
    }

}