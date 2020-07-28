<?php


use App\Helpers\Hash;
use App\Admin\Auth;
use App\Action\Urls\Controller;

# use App\Core\Engine\Router\Request;

# Create all action method in the Controller class
# all method from the must must return
# either a view or a content
/** 
 * @param 'optional' [Request $request]
 * # used when an action is used on a post request
 * @example creating an action method
 * public function index(){
 * @param 'filename' in the views folder
 *     return view("index");
 * }
 * */ 

class AuthController extends Controller {
    
    # GET/POST
    public function login($request){

        $data = ["title" => "Login"];

        if($request->method === "POST"){
            $user = new User;

            # Login details
            $username = $request->username;
            $password = $request->password;

            # check user if exists
            $get_email = $user->select()->where(["email" => $username]);
            $get_user = $user->select()->where(["username" => $username]);
            if($get_email || $get_user){

                # Auth login 
                $get_user = ($get_email) ? $get_email : $get_user;
                if(!Hash::verify($password, $get_user->password)) {
                    $data["password_incorrect"] = true;
                    return view("auth/login", $data);
                }

                
                if(isset($request->keep_logged)){
                    
                }

                if($get_user->is_admin) {
                    $permissions = $get_user->admin()->role()->role_permissions()->pickAll();

                    $list = [];
                    foreach ($permissions as $permission) {
                        # code...
                        $list[$permission->permission->name] = 1;
                    }

                    $get_user->permissions = $list;
                }
                
                Auth::login($get_user);

                if(isset($request->param["page"])) {
                    return redirect("/".$request->redirect);
                }
                return redirect("/users/account");
            }

            $data["not_exists"] = true;
            return view("auth/login", $data);
        }

        if(isset($request->redirect)) {
            $data["redirect"] = $request->redirect;
        }

        return view("auth/login", $data);
    }

    public function register($request)
    {
        $data = ["title" => "Register"];

        # Registration
        if($request->method == "POST"){
            $user = new User;
            # User Details
            $username = $request->username;
            $email = $request->email;
            $password = $request->password;

            # check username and email existence
            $email_check = $user->select()->where(["email"=>$email]);
            ($email_check) ? $email_check = true : $email_check = false; 

            $username_check = $user->select()->where(["username"=> $username]);
            ($username_check) ? $username_check = true : $username_check = false;

            # if username and email exists
            if($email_check || $username_check){
                # Return error result
                $errors = [];
                if($email_check) { $errors["email_check"] = $email_check; }
                if($username_check) { $errors["username_check"] = $username_check; }
                return view("auth/register", array_merge($data, $errors));
            } 
            else {
                # if username and email does not exists
                # register new user account
                $user_id = rand(124567,987688);
                $register = $user->insert([
                    "user_id" => $user_id,
                    "username" => $username,
                    "email" => $email,
                    "password" => Hash::create($password)
                ]);
                
                if($register){
                    # Registration successfull
                    Auth::login($user);

                    if(isset($request->redirect)) {
                        return redirect("/".$request->redirect);
                    }
                    return redirect("/users/account");
                } else {
                    # Error while registering user
                    $data["error"] = true;
                    return view("auth/register",$data);
                }
            } 


        }

        if(isset($request->redirect)) {
            $data["redirect"] = $request->redirect;
        }
        
        return view("auth/register", $data);
    }

    public function forget_password($request)
    {

        // if(isset($request->redirect)) {
        //     $data["redirect"] = $request->redirect;
        // }
        return view("auth/forgot-password");
    }

    public function logout(){
        Auth::logout();
        Session::endall();
        return redirect("/shop");
    }
}