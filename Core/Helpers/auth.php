<?php

use App\Admin\Door;

if(!function_exists("auth")) 
{
    /** 
     * 
     * @return Auth::user|null
    */
    function auth() {

        if(Auth::user() != null)
         {
            return Auth::user();
        }

        return null;
    }
}

if(!function_exists("access")) 
{
    /** 
     * gives access with permission
     * 
     * @param string $lock
     * @return bool
    */

    function access($lock)
    {
        return Door::hasLock($lock);
    }

}