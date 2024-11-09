<?php
namespace App\Http\Controllers\Traits;
use App\Models\User;

trait TraitsUser {
    
    public function TraitsUser($email,$password,$request) {

        $user = User::where('email',$email)->where('password',$password)->first();

        if($user)
        {
            auth()->loginUsingId($user->id);
            
            if(auth()->user())
            {
                $request->session()->regenerate();
                return true;
            }
            else return false;
        }
        else if($request->isMethod('Post') && !$user)
        {
            //If The User Is not Logged In.
            return abort(404);
        };
    }
}