<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next):Response
    {
        $user = Auth::user();
        if ($user === null){
            return response(['errors'=>['login'=>['Not logged In']]],403);
        }
        if ( ! AdminController::isAdmin($user)){
            return response(['errors'=>['admin'=>['Not an Admin user']]],403);
        }
        return $next($request);
    }
}
