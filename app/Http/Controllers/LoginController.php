<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;

class LoginController extends Controller
{
    public function checkLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
  
        $user_data = array(
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        );
    
        if(Auth::attempt($user_data))
        {
            return redirect('home');
        }
        else
        {
            return back()->with('error', 'Wrong Login details');
        }
    }
  
  
    public function login()
    {
        return view('home');
    }
  
    public function plain()
    {
        //return view('dashboard');
        return "hello plain user";
    }
}
