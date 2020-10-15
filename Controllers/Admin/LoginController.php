<?php

namespace App\Action\Urls\Controllers\Admin;


use App\Action\Urls\Controllers\Controller;
use App\Core\Urls\Request;
use Auth;
use App\Admin\Door;
use App\Hashing\Hash;
use App\Product;
use App\Role;
use App\User;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

 
class LoginController extends Controller {

    public function __construct()
    {
        if(Auth::user()) {
            return redirect("/dashboard");
        }
    }

    public function index(Request $request)
    {

        $response = ["page_view" => "Auth"];

        if($request->method == "POST")
        {
            $request->required([
                "email" => "email",
                "password" => "string"
            ]);

            if($request->validation == true)
            {
                $user = (new User)->where(["email" => $request->email, "is_admin" => 1])->get();

                if($user) 
                {
                    if(Hash::verify($request->password, $user->password))
                    {
                        $role = (new Role)->where("id", $user->role)->get();
                        $permissions = $role->permissions();

                        if($permissions != null) {
                            $locks = array();

                            foreach($permissions as $permission) {

                                array_push($locks, strtolower($permission->permission()->name));
                            }

                            Door::createLocks($locks);
                        }

                        Auth::login($user);
                        return redirect("dashboard");
                    }
                    else 
                    {
                        $response["message"] = "Incorrect password or email address entered!";
                    }
                } 
                else 
                {
                    $response["message"] = "Account not found!";
                }

            }

        }

        return view("admin/login/index", $response);
    }

    public function logout() {
        Auth::logout();
        return redirect("signin");
    }

}