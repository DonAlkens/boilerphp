<?php
namespace App\Admin;


use Exception, Session;


class Door {


    static $doors = array();


    public function __construct()
    {
        
    }

    public function openWith($lock, $callback)
    {
        if(Session::get("app_doors_locks"))
        {
            $doors = Session::get("app_doors_locks");

            if(!isset($doors[$lock])) 
            {
                if(!is_callable($callback))
                {
                    throw new Exception("Callback expected on ".__LINE__);
                } 
                else 
                {
                    $callback();
                    die();
                }

            }
        }
        
        else 
        {

            throw new Exception("Doors does not exists.");
        }

    }

    static public function createLocks(array $locks)
    {
        foreach($locks as $lock) 
        {
            static::lock($lock);
        }

        static::lockDoors();
    }

    static public function lock($lock)
    {
        if(!key_exists($lock, static::$doors)) 
        {
            static::$doors[$lock] = true;
        }
        else 
        {
            // throw duplicate door creation exception
            throw new Exception("Duplicate Locks not allowed.");
        }
    }

    static private function lockDoors()
    {
        Session::set("app_doors_locks", static::$doors);
    }

    static public function hasLock($lock) 
    {
        if(Session::get("app_doors_locks")) 
        {
            if(isset(Session::get("app_doors_locks")[$lock]))
            {
                return true;
            }
        }

        return false;
    }

}