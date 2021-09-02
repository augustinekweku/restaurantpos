<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {

        
        //validate request
        $this->validate($request, [
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, ])) {
            $user = Auth::user();
            //return $user;          
            return response()->json([
                'msg' => 'You are logged in',
                'user' => $user
            ]);
        }else {
            return response()->json([
                'msg' => 'Incorrect Login details '
            ], 401);    }
    }


    public function logout() 
    {
        Auth::logout();
        return redirect('/login');
    }
}
