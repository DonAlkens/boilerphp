<?
namespace App\Action\Urls\Controllers;


use App\Admin\Door;
use App\FileSystem\Fs;
use App\Core\Urls\Request;

/** 
 * @param 'optional' [Request $request]
 * used to get action's request data get/post
 */ 

class HomeController extends Controller {

    public function index()
    {
        return view("home");
    }

}