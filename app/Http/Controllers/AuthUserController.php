<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AuthUserController extends UsersController
{
    public function doLogin(Request $request){
      $auth = Auth::attempt([
        'username'=>$request->username,
        'password'=>$request->password
      ]);

      if($auth){
        return redirect('/dashboard');
      }else{
        return view('/login')->withErrors(['The username or password you have entered is wrong!']);
      }
    }

    public function Logout(Request $request){
      Auth::logout();
      return redirect('/login');
    }
}
