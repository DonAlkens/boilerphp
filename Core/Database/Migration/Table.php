<?php

namespace App\Core\Database\Migration;

use App\Core\Database\Schema;

class Table extends Schema {

    protected static $dbkey = null;

    public function __construct($key = null)
    {
        static::$dbkey = $key;
    }

    public static function connection($name)
    {
        return new Table($name);
    }

    public static function create($name, $callback)
    {
        $diagram = new Diagram($name);
        $callback($diagram);
        $tableQuery = $diagram->createTableQuery(
            $name, $diagram->trimmer($diagram->query), $diagram->trimmer($diagram->primary_keys)
        );
        
        
        if(static::$dbkey == null) {
            static::$dbkey = "default";
        }
        
        $diagram->foreignKeyProccessor($name);
        (new Schema)->db(static::$dbkey)->run($tableQuery);
    }

    public static function modify($name) {

        return (new Table)->setTable($name);
    }

    public static function dropIfExists($table)
    {
        $diagram = new Diagram($table);
        $tableQuery = $diagram->dropTableQuery();

        if(static::$dbkey == null) {
            static::$dbkey = "default";
        }

        (new Schema)->db(static::$dbkey)->run($tableQuery);
    }

}