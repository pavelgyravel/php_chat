<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Input;
use Auth;
use Validator;
use Hash;

class AdminController extends Controller
{
    public function index($id) {

    	$user = User::findOrFail($id);
    	return view('admin.user', compact('user'));
    }

    public function update($id) {
    	$user = User::findOrFail($id);

    	$rules = [
        	'name' => 'required|max:255',
        ];
        
        if (Input::get('password')) {
        	$rules['password'] = 'required|confirmed|min:6';
        }

		$v = Validator::make(Input::all(), $rules);

		if ($v->fails()) {
			$errors = $v->messages();
			return view('admin.user', compact('user', 'errors'));
		} else {
			
			$user->name = Input::get('name');
			$user->blocked = Input::get('blocked'); 
			
			if (Input::get('password')) {
        		$user->password = Hash::make(Input::get('password')); 
        	}			
        	$user->save();

			return redirect('/messages');
		}
    }

    public function delete($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/messages');
    }
}
