<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

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
            return Helper::errResponse(403,'login','err-not_logged_in');
        }
        if ( ! $user->isAdmin() ){
            return Helper::errResponse(403,'admin','err-not_admin_user');
        }
        return $next($request);
    }
}
