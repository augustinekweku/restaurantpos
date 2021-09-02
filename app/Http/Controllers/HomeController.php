<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function index(Request $request) {
        // first check if u are logged in as admin user
        if (!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        }
        if (!Auth::check() && $request->path() == 'login') {
            return view('welcome');
        }
        //you are already logged in so check if you are an admin user
        $user = Auth::user();
        // return $request->path();
        // return $user->role_id;
        if ($request->path() == 'login') {
            return redirect('/');
        }
        return view('welcome');
    }
}
