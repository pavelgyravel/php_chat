<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Hash;

class UserController extends Controller
{
	public function index() {
		$user = Auth::user();
		return view('user.profile', compact('user'));
	}


	public function update(Request $request) {
		$user = Auth::user();
        $rules = [
        	'name' => 'required|max:255',
        ];
        
        if (Input::get('password')) {
        	$rules['password'] = 'required|confirmed|min:6';
        }

		$v = Validator::make(Input::all(), $rules);
		
		if ($v->fails()) {
			$errors = $v->messages();
			return view('user.profile', compact('user', 'errors'));
		} else {
			
			$user->name = Input::get('name'); 
			
			if (Input::get('password')) {
        		$user->password = Hash::make(Input::get('password')); 
        	}			
        	$user->save();

			return redirect('/messages');
		}
		

		
	}
}
