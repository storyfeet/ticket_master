<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function home(){
        $user = Auth::user();
        if ($user != Null){
            return view('HomePage',["user"=>$user]);
        }
        return view('HomePage');
    }
    //
    //
    public function loginhome(){
        return view('LoginPage');
    }

    public function login(){
        validator(request()->all(),[
            'email' => ['required','email'],
            'password' => ['required']
        ]
        )->validate();

        if (! auth()->attempt(request()->only(['email','password']))){
            return redirect()->back()->withErrors(["email" => "Invalid Credentials"]);
        }

        return redirect('/');
    }

    public function logout(){
        auth()->logout();
        return redirect('/');
    }
}
