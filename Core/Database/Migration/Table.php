<?php

namespace App\Core\Database\Migration;

use App\Core\Database\Schema;

class Table extends Schema {

    protected static $dbkey = "default";

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
            $diagram->trimmer($diagram->query), $diagram->trimmer($diagram->primary_keys)
        );

        $foreignKeysQuery = $diagram->foreignKeyProccessor($name);
        Table::createAlters($foreignKeysQuery);
        (new Schema)->db(static::$dbkey)->run($tableQuery);
    }

    public static function modify($name, $callback) {

        $diagram = new Diagram($name);
        $diagram->setPkMode(false);

        $callback($diagram);
        $query = $diagram->modifyTableQuery(
            $diagram->trimmer($diagram->query), $diagram->trimmer($diagram->primary_keys)
        );

        $foreignKeysQuery = $diagram->foreignKeyProccessor($name);
        Table::createAlters($foreignKeysQuery);

        (new Schema)->db(static::$dbkey)->run($query);
    }

    private static function createAlters($foreignKeysQuery) 
    {
        if($foreignKeysQuery != "" && !is_null($foreignKeysQuery)) {
            array_push(static::$alters, $foreignKeysQuery);
        }
    }

    public static function getAlters() {
        return static::$alters;
    }

    public static function dropIfExists($table)
    {
        if(static::$dbkey == null) {
            static::$dbkey = "default";
        }

        (new Schema)->db(static::$dbkey)->dropDatabaseTable($table);
    }

}