<?php

use App\Core\Urls\Request;
use App\Action\Urls\Controller;
use App\Admin\Auth;
use App\Hashing\Hash;
use App\Role;
use  App\User;
/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class Admin_LoginController extends Controller {

    public function __construct()
    {
        // $this->hasAuthAccess("user", "login");
    }

    public function index(Request $request)
    {

        if($request->method == "POST")
        {
            $request->required([
                "email" => "email",
                "password" => "string"
            ]);

            if($request->validation == true)
            {
                $user = new User;
                $user = $user->where("email", $request->email)->get();

                if($user) 
                {
                    if(Hash::verify($request->password, $user->password))
                    {
                        $role = new Role;
                        $role = $role->where("id", $user->role)->get();

                        Auth::login($user);

                        return redirect("dashboard");
                    }
                }
            }



        }

        return view("admin/login/index", ["page_view" => "Auth"]);
    }

}