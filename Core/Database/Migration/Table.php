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

        $diagram->foreignKeyProccessor($name);
        (new Schema)->db(static::$dbkey)->run($tableQuery);
    }

    public static function modify($name, $callback) {

        $diagram = new Diagram($name);
        $callback($diagram);

        $query = $diagram->modifyTableQuery(
            $diagram->trimmer($diagram->query), $diagram->trimmer($diagram->primary_keys)
        );

        $diagram->foreignKeyProccessor($name);
        (new Schema)->db(static::$dbkey)->run($query);
    }

    public static function dropIfExists($table)
    {
        if(static::$dbkey == null) {
            static::$dbkey = "default";
        }

        (new Schema)->db(static::$dbkey)->dropDatabaseTable($table);
    }

}