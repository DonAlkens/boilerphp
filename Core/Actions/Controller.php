<?php

namespace App\Action\Urls;

use Exception;

use App\Admin\Auth;	

class Controller {

	public function __construct(){

	}

	public function hasAuthAccess($name, $redirect)
	{
		if(isset($_SESSION[$name])){
			$logger = true;
		} 
		else {
			return redirect($redirect);
		}
	}

	public function hasPermission($permission, $redirect) {
		$permissions = Auth::user()->permissions;
		if(!array_key_exists($permission, $permissions)) {
			return redirect($redirect);
		}
	}

	public function hasPermissions($list, $redirect) {
		$permissions = Auth::user()->permissions;
		foreach($list as $permission) {
			if(!array_key_exists($permission, $permissions)) {
				return redirect($redirect);
			}
		}
	}
}



