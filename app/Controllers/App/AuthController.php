<?php

namespace App\Action\Urls\Controllers\App;


use App\Action\Urls\Controllers\Controller;
use App\Core\Urls\Request;
use App\Country;
use App\DialCode;
use App\Hashing\Hash;
use App\Notification\PasswordResetNotification;
use App\Notification\RegistrationNotification;
use App\PasswordReset;
use App\Role;
use App\User;
use App\UserDetail;
use Auth;
use Cookie;
use Session;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class AuthController extends Controller {

    public function index() {
        
        return view("app/auth/index");
    }

    public static function auth_check(Request $request) 
    {
        // Auth::logout();
        if(Cookie::fetch("_apuid")) 
        {
            $user_id = Cookie::fetch("_apuid");
            $user = (new User)->find($user_id);
            Auth::login($user);

            $continue = "https://".env('DOMAIN_NAME');
            if(isset($request->continue))
            {
                $continue = $request->continue;
            }
            return redirectToHost($continue);
        }
    }

    public function sign_in(Request $request) 
    {
        
        $data["title"] = "Sign In";

        $params = "?".explode("?", url())[1];
        $data["params"] = $params;

        if($request->method == "GET")
        {
            if(isset($request->continue) && isset($request->service))
            {
                Session::set("app_service", $request->service);
                Session::set("app_continue", $request->continue);
            }
            else 
            {
                Session::set("app_service", "marketplace");
                Session::set("app_continue", "https://".env('DOMAIN_NAME'));
            }
        }


        if($request->method == "POST") 
        {
            $request->service = Session::get("app_service"); Session::end("app_service");
            $request->continue = Session::get("app_continue"); Session::end("app_continue");

            if(isset($request->email) && isset($request->password)) 
            { 

                if((new User)->exists($request->email)) 
                {

                    $user = (new User)->find("email", $request->email);

                    if(Hash::verify($request->password, $user->password)) 
                    {

                        if($user->blocked == 0) 
                        {
                            Auth::login($user);

                            Cookie::create("_apuid", $user->id, 432000, "/", env('DOMAIN_NAME'));
                            // Cookie::expire("_gl", 432000, env('DOMAIN_NAME'));

                            if($request->service == "sup") { 
                                // Enable Live Chat Activities
                                Cookie::create("is_livechat_support", true, 432000, "/", env('DOMAIN_NAME')); 
                            }

                            if($user->verified == 0) 
                            {
                                (new RegistrationNotification($user, $params))->send();
                                
                                return redirect("/verification");
                            }

                            if($request->service == "sell") 
                            {
                                if($user->is_vendor == "0") 
                                {
                                    if($user->phone == "") {}
                                    return redirectToHost("https://sellercenter.".env('DOMAIN_NAME')."/request-to-sell");
                                }
                            }

                            return redirectToHost($request->continue);

                        }
                        else{
                            flash("error", "This account has been suspended!");
                        }

                    }
                    else 
                    {
                        flash("error", "Incorrect email or password!");
                    }
                }
                else 
                {
                    flash("error", "Account not found!. <a href='https://account.".env('DOMAIN_NAME')."/sign-up". $params ."'>Click to create account</a>");
                }

            }

        }


        static::auth_check($request);
        return view("auth/sign-in", $data);
    }

    public function sign_up(Request $request) 
    {
        
        $data["title"] = "Sign up";        

        $params = "?".explode("?", url())[1];
        $data["params"] = $params;

        if($request->method == "GET")
        {
            if(isset($request->continue) && isset($request->services))
            {
                Session::set("app_service", $request->service);
                Session::set("app_continue", $request->continue);
            }
            else 
            {
                Session::set("app_service", "marketplace");
                Session::set("app_continue", "https://".env('DOMAIN_NAME'));
            }
        }

        if($request->method == "POST") 
        {
            $request->service = Session::get("app_service"); Session::end("app_service");
            $request->continue = Session::get("app_continue"); Session::end("app_continue");

            if(isset($request->email) && isset($request->fullname) && isset($request->password) && isset($request->cpassword)) 
            {

                if((new User)->exists($request->email))
                {
                    flash("error", "Email address already exists!");
                }
                else 
                {
                    $password = Hash::create($request->password);
                    $user = (new User)->new($request->fullname, $request->email, $password);
                    
                    if($user)
                    {
                        Auth::login($user);
                        Cookie::create("_apuid", $user->id, 432000, "/", env('DOMAIN_NAME'));

                        // "country" => $request->codeid
                        (new UserDetail)->insert(array("user_id" => $user->id));

                        (new RegistrationNotification($user, $params))->send();
                        Session::set("verify_user", $user->id);

                        return redirect("/step/verification{$params}");
                        // return redirectToHost($request->continue);

                    } 
                    else 
                    {
                        flash("error", "Unable to create account. Please try again!");
                    }
                }

            }

        }

        // $countries = (new Country)->query("SELECT * FROM countries ORDER BY name ASC");
        // $countries = $countries->fetchAll();

        // $list = [];
        // foreach($countries as $country) {
        //     $country = json_encode($country);
        //     $country = json_decode($country);
        //     $get_dial = (new DialCode)->find($country->phone_code);
        //     if($get_dial) {
        //         $country->phone_code = $get_dial->phone_code;
        //         array_push($list, $country);
        //     }

        //     continue;
        // }

        // $data["countries"] = $list;

        static::auth_check($request);
        return view("auth/sign-up", $data);
    }

    public function verify(Request $request)
    {
        $data["title"] = "Account Verification";
        $data["header"] = "Account Verification";

        $data["params"] = "?service=marketplace&continue=https://".env('DOMAIN_NAME');
        
        if(isset($request->continue)) { $data["continue"] = $request->continue; }
        if(isset($request->service)) { $data["service"] = $request->service; }

        if(isset($request->service) && isset($request->continue)) {
            $data["params"] = "?service={$request->service}&continue={$request->continue}";
        }

        $user = (new User)->find($request->param["id"]);

        if($user) 
        {
            if($user->verified != 1)
            {
                if($user->update(["verified" => 1])) 
                {
                    Session::end('verify_user');

                    $data["state"] = true;
                    $data["message"] = "Account has been verified successfully.";
                }
                else{
                    $data["state"] = false;
                    $data["message"] = "Error occured. Unable to confirm account.";
                }
            }
            else 
            {
                if(auth()) {
                    return  redirectToHost("https://".env('DOMAIN_NAME')."/uac/dashborad");
                }
                // return redirect("/sign-in?service=marketplace&continue=https://".env('DOMAIN_NAME'));
                
                $data["state"] = false;
                $data["message"] = "This account has already been verified earlier. 
                Kindly proceed to <a href='/sign-in".$data["params"]."'><b>Login here</b></a>.";
                $data["reclick"] = true;

            }

        }
        else 
        {
            return error404();
        }

        return view("auth/verify", $data);
    }

    public function verification(Request $request)
    {

        $params = "?".explode("?", url())[1];
        $data["params"] = $params;

        if (Session::get("verify_user")) 
        {
            $data["title"] = "Verify Account";
            $data["header"] = "Verify Account";

            $data["continue"] = $request->continue;
            $data["service"] = $request->service;

            $id = Session::get("verify_user");
            $user = (new User)->find($id);

            $data["user"] = $user;
            return view("auth/verification", $data);
        }

        return redirectToHost("https://wearslot.com");
    }

    public function forgot(Request $request) 
    {

        $data["title"] = "Forget Password";

        $params = "?".explode("?", url())[1];
        $data["params"] = $params;

        if($request->method == "POST") 
        {
            $request->required([
                "email" => "email"
            ]);
    
            if($request->validation == true) {

                $user = (new User)->find("email", $request->email);
                if($user) 
                {

                    $code = rand(123768, 987564).date("YdmHis");
                    $code = Hash::create($code, true);
                    $new_password_reset = [
                        "user_id" => $user->id,
                        "code" => $code,
                    ];

                    $passwordReset = (new PasswordReset)->insert($new_password_reset);
                    if($passwordReset) 
                    {
                        if((new PasswordResetNotification($user, $passwordReset, $params))->send()) 
                        {
                            return redirect("/fconfirmed");
                        }
                    }

                }
                else 
                {
                    flash("error", "Account not found!. Check the email and try again.");
                }

            }

        }

        static::auth_check($request);
        return view("auth/forgot-password", $data);
    }

    public function fconfirmed(Request $request) 
    {
        return view("auth/fconfirmed");
    }

    public function change(Request $request) 
    {

        $data["title"] = "Change Password";

        $id = $request->param["id"];
        $code = $request->param["code"];

        $params = "?".explode("?", url())[1];
        $data["params"] = $params;

        if($request->method == "GET")
        {
            Session::set("app_service", $request->service);
            Session::set("app_continue", $request->continue);
        }

        $user = (new User)->find($id);
        $reset = (new PasswordReset)->where(["code" => $code, "user_id" => $user->id])->get();

        if($reset) 
        {

            $time = get_time_diff($reset->created_date);

            if($time->days < 0 || $time->hours < 0 || $time->mins < -30 || $reset->status == 1) { 
                $reset->update(["expired" => 1]);
                $data["expired"] = true;
            }
    
            if($request->method == "POST") 
            {

                $request->service = Session::get("app_service"); Session::end("app_service");
                $request->continue = Session::get("app_continue"); Session::end("app_continue");

                $request->required([
                    "password" => "string",
                    "confirm_password" => "string"
                ]);

                if($request->validation == true) 
                {

                    if($request->password == $request->confirm_password) 
                    {

                        $password = Hash::create($request->password);
                        if($user->update(["password" => $password])) 
                        {

                            $reset->update(["status" => 1]);

                            Auth::login($user);
                            Cookie::create("_apuid", $user->id, 432000, "/", env('DOMAIN_NAME'));

                            
                            $data["heading"] = "Password Changed";
                            $data["state"] = true;
                            $data["message"] = "Password has been updated successfully";


                            return view("auth/password-success", $data);

                        }

                    }
                    else 
                    {
                        flash("error",  "Password does not match!");
                    }

                }
    
            }
    
            return view("auth/change-password", $data);
        }

        return error404();

    }
}