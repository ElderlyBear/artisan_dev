<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
class CheckStory
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
            return response()->json(['message' => 'User not found'], 404);
        }

        if($user->roles()->where('name', 'Admin')->exists()){
            return $next($request);
        }else{
            return response()->json(['message'=> "You don't have permission to access this page"], 403);
        }

    }
}
