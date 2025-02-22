<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Home Controller manages the simple details of the homepage, and
 * whether or not users are logged in
 */
class HomeController extends Controller
{




    /**
    * Check the user's logged in status and
    * return the appropriate home-page
    */
    public function home(){
        $arr = ['userInfo' => UserController::userInfo()];
        $mes = session()->get('message');
        if ($mes != Null){
            $arr["message"] = $mes;
        }

        return view('HomePage',$arr);
    }


    /**
    * Handle the login request.
    */
    public function login(){
        validator(request()->all(),[
            'email' => ['required','email'],
            'password' => ['required']
        ]
        )->validate();

        if (! auth()->attempt(request()->only(['email','password']))){
            return redirect()->back()->withErrors(["email" => "err-invalid_credentials"]);
        }

        return redirect('/');
    }

    public function loginJSON(){
        //TODO unfinished function
        try{
            validator(request()->all(),[
                'email' => ['required','email'],
                'password' => ['required']
            ]
            )->validate();
        }catch (ValidationException $e){
            return ['errors'=>$e->errors()];
        }

        if (! auth()->attempt(request()->only(['email','password']))){
            return ['errors'=>['credentials'=>['err-invalid_credentials']]];
        }

        return UserController::userInfo();
    }



    /**
    * Handle the logout request and return the user to the public homepage
    */
    public function logout(){
        auth()->logout();
        return redirect('/');
    }
}
