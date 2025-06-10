<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use function Laravel\Prompts\warning;

class Check2fa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if(!$user){
            return response()->json(['message' => 'User not found'], 404);
        }

        $password = $request->input('password');

        if(!$password){
            return response()->json(['message' => 'Password is required'], 404);
        }

        if(Hash::check($password, $user->password)){
            return $next($request);
        }else{
            return response()->json(['message' => 'Wrong password'], 404);
        }

    }
}
