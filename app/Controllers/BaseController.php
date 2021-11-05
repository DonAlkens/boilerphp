<?php

namespace App\Action\Urls\Controllers;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */

use App\Category;
use App\Core\Urls\Request;
use App\SubCategory;
use App\User;

class BaseController extends Controller 
{

    public function index() {

        $users = (new User)->all();
        $category = (new Category)->all();
        $subs = (new SubCategory)->all();

        foreach($category as $cat) {
           $sub = $cat->subs();
           if($sub) {
               foreach($sub as $s) {
                   echo $s->name."<br>";
               }
           }
            
        }

        // return view("index");
    }

}