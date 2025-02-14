<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Role;
use Illuminate\Validation\ValidationException;

/**
 * Home Controller manages the simple details of the homepage, and
 * whether or not users are logged in
 */
class HomeController extends Controller
{

    /**
    * Return whether or not the user is an admin
    */
    public static function isAdmin($user):bool{
        return Role::query()
            ->where('user','=',$user->id)
            ->where('role','=','admin')
            ->count() > 0;
    }

    public function userInfo(){
        $user = Auth::user();
        if ($user === null) return null;
        $isAdmin = $this->isAdmin($user);
        return [
            'id'=>$user->id,
            'name'=> $user->name ,
            'email'=> $user->email,
            'isAdmin' => $isAdmin,
        ];
    }


    /**
    * Check the user's logged in status and
    * return the appropriate home-page
    */
    public function home(){
        $arr = ['userInfo' => $this->userInfo()];
        $mes = session()->get('message');
        if ($mes != Null){
            $arr["message"] = $mes;
        }

        return view('HomePage',$arr);
    }

    /**
    * Return the view for the login form
    */
    public function loginhome(){
        return view('LoginPage');
    }

    /**
    * Return the view for users to create tickets.
    * User must be logged in to see this form
    */
    public function ticketform(){
        $user = Auth::user();
        if ($user != Null){
            return view('TicketForm',["user"=>$user]);
        }
        return redirect('/');
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
            return redirect()->back()->withErrors(["email" => "Invalid Credentials"]);
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
            return ['errors'=>['credentials'=>'Login Credentials not correct']];
        }

        return $this->userInfo();
    }



    /**
    * Handle the logout request and return the user to the public homepage
    */
    public function logout(){
        auth()->logout();
        return redirect('/');
    }
}
