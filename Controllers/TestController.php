<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;
use App\Hashing\Hash;
use App\Permission;
use App\Role;
use App\RolePermissions;
use App\User;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class TestController extends Controller 
{

    public static $user;

    
    public static $role;


    public static $permission;

    public static $PRs;


    public function __construct()
    {
        static::$user = new User;
        static::$role  = new Role;
        static::$permission = new Permission;
        static::$PRs = new RolePermissions;

        //$this->hasAuthAccess("user", "login");
    }

    public function index()
    {

        $user_data = array(
            "firstname" => "Akinpelumi", 
            "lastname" => "Akinniyi",
            "email" => "akinniyiakinpelumi@gmail.com",
            "password" => Hash::create("akinniyi"),
            "is_admin" => 1,
            "role" => 1,
        );

        static::$user->insert($user_data);
        
    }

    public function roles()
    {

        $role_data = array(
            array(
                "name" => "Administrator",
                "created_by" => 1
            ),
            array(
                "name" => "Vendor",
                "created_by" => 1
            )
        );

        foreach ($role_data as $role) {
            # code...
            static::$role->insert($role);
        }

    }

    public function permissions()
    {

        $permission_data = array(
            array(
                "name" => "Manage Users",
                "created_by" => 1
            ),
            array(
                "name" => "Manage Admins",
                "created_by" => 1
            ),
            array(
                "name" => "Manage Products",
                "created_by" => 1
            ),
            array(
                "name" => "Manage Orders",
                "created_by" => 1
            ),
            array(
                "name" => "Manage Transactions",
                "created_by" => 1
            ),
            array(
                "name" => "Manage Variations",
                "created_by" => 1
            ),
            array(
                "name" => "Manage Collections",
                "created_by" => 1
            )
        );

        $index = 1;
        foreach ($permission_data as $permission) {
            # code...
            static::$permission->insert($permission);

            $data = array(
                "role_id" => 1,
                "permission_id" => $index
            );

            static::$PRs->insert($data);

            $index++;
        }

    }

}