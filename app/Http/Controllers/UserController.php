<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{

    public function show(Request $request) {
    	$username = $request->username;
		$password = md5($request->password);
    	try {
	    	// $user = User::where('username', '=', $username)->where('password','=', $password)->first();
	    	$user = User::where([
	    		['username', '=', $username],
	    		['password', '=', $password]
	    	])->first();
	    	if(!empty($user)) {
		    	return response()->json([
		    		'error'		=> '',
		    		'data'		=> $user,
		    		'message'	=> ''
		    	], 200);
		    } else {
		    	return response()->json([
		    		'error'		=> '',
		    		'data'		=> '',
		    		'message'	=> 'Tidak ditemukan'
		    	], 204);
		    }
    	} catch (Exception $e) {
    		return response()->json('Terjadi kesalahan, silahkan ulangi beberapa saat lagi');		
    	}
    }
}
