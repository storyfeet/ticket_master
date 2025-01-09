<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function home(){
        $arr = [];
        $user = Auth::user();
        if ($user != Null){
            $arr["user"] = $user;
        }
        $mes = session()->get('message');
        if ($mes != Null){
            $arr["message"] = $mes;
        }
        return view('HomePage',$arr);
    }
    //
    //
    public function loginhome(){
        return view('LoginPage');
    }

    public function ticketform(){
        $user = Auth::user();
        if ($user != Null){
            return view('TicketForm',["user"=>$user]);
        }
        return redirect('/');
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
