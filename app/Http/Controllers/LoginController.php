<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('auth.login');
    }

    public function loginIn(Request $request){
        // dd($request->all());
        try{
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $credentials = [
                'username' => $request->username,
                'password' => $request->password,
            ];

            if(Auth::attempt($credentials, $request->rememberMe)){
                return redirect()->route('dashboard');
            }
            return redirect()->back()->with('warning', 'Wrong Credentials');
        }catch(Exception $e){
            // dd($e);
            return redirect()->back()->with('error', 'Something Went Wrong! ', $e->getMessage());
        }
    }
}
