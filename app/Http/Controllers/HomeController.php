<?php

namespace App\Http\Controllers;
use Validator;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function dashb()
    {
        return view('dashboard');
    }

    public function check()
    {
        if(Auth::check())
        {
            return back();
        }
        else
        {
            return view('/');
        }
    }
}
