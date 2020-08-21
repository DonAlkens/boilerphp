<?php

use App\Admin\Auth;	

if(!function_exists("auth")) 
{
    /** 
     * 
     * @retutn App\Admin\Auth::user|null
    */
    function auth() {

        if(Auth::user() != null)
         {
            return Auth::user();
        }

        return null;
    }
}