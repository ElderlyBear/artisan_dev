<?php

namespace App\Http\Controllers;

use App\DTOs\loginDTO;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\DTOs\registerDTO;
use Illuminate\Support\Facades\Auth;
use App\DTOs\getUserDTO;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        $dto = new registerDTO(
            $request->input('name'),
            $request->input('email'),
            $request->input('password')
        );

        return DB::transaction(function () use ($dto){
            $user = User::create([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => Hash::make($dto->password)
            ]);

            return response()->json(['message' => 'User registered successfully.'], 201);
        });
    }

    public function login(LoginRequest $request){
        $request->validate([
            'code' => 'nullable|string',
        ]);

        $dto = new loginDTO($request->input('email'), $request->input('password'));
        $user = User::where('email', $dto->email)->first();

        $google2fa = new Google2FA();

        if($user->is_two_factor_auth){
            $userCode = $request->input('code');

            if($google2fa->verifyKey($user->two_factor_secret, $userCode)){
                $token = $user->createToken('token')->plainTextToken;
                return response()->json(['message' => 'User logged in successfully.', 'token'=> $token], 200);
            }else{
                return response()->json(['message' => 'Wrong code.'], 401);
            }
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json(['token' => $token], 200);

    }

    public function login2fa(){

    }

    public function me(Request $request){
        $user = $request->user();
        $dto = new getUserDTO($user->name, $user->email);

        return response()->json(['user' => $dto], 200);
    }

    public function out(){
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.'], 200);
    }

    public function tokens(){
        $user = Auth::user();
        return response()->json(['tokens' => $user->tokens()->get()], 200);
    }

    public function out_all(){
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json(['message' => 'Logged all tokens out successfully.'], 200);
    }

}
