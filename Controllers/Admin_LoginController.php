<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;
use App\Admin\Auth;
use App\Hashing\Hash;
use App\Role;
use App\User;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

 
class Admin_LoginController extends Controller {

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
                $user = (new User)->where("email", $request->email)->get();

                if($user) 
                {
                    if(Hash::verify($request->password, $user->password))
                    {
                        $role = (new Role)->where("id", $user->role)->get();

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