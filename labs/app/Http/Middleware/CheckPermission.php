<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('sanctum')->user();

        if(!$user){
            return response()->json(['message'=> 'Пользователь не авторизован']);
        }

        if($user->roles()->where('name', 'Admin')->exists()){
            return $next($request);
        }else{
            return response()->json(['message'=> 'This place only for Admin']);
        }

    }
}
