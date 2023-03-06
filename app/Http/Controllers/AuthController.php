<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AccountResource;

class AuthController extends Controller
{
    public function account () {
        $user = auth()->user()->load("info:user_id,address,phone");
        return new AccountResource($user);
    }

    public function login (LoginRequest $req) {
        $credentials = $req->validated();
        if (!Auth::attempt($credentials)) {
            return response()->json(["errors"=>['password'=>'Password incorrect']], 401);
        }
        // get current user
        $user = Auth::user();
        // delete user tokens
        $user->tokens()->delete();
        // check remember exist & true or false
        $isRemember = filter_var($req['remember'], FILTER_VALIDATE_BOOLEAN);
        // create token
        $token = $this->createAuthToken($user, $isRemember);
        return response()->json(['user'=>$user,'token'=>$token->plainTextToken, 'remember'=>$isRemember], 200);
    }

    public function adminLogin (LoginRequest $req) {
        $credentials = $req->validated();
        if (!Auth::attempt($credentials)) {
            return response()->json(["message"=>'Password incorrect'], 401);
        }
        // get current user
        $user = Auth::user();
        // delete user tokens
        $user->tokens()->delete();
        // create token
        $token = $this->createAuthToken($user, true);
        return response()->json(['token'=>$token->plainTextToken], 200);
    }

    public function register (RegisterRequest $req) {
        $data = $req->validated();
        // create new user
        $user = User::create([
            'username' => $data['username'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
        // remember to false
        $isRemember = false;
        // create token
        $token = $this->createAuthToken($user, $isRemember)->plainTextToken;
        return response()->json(['user'=>$user,'token'=>$token], 200);
    }

    public function logout (Request $req) {
        $user = $req->user();
        if (!$user) {
            return response()->json(['error'=>'no user']);
        }
        $user->currentAccessToken()->delete();
        return response()->noContent();
    }

    protected function createAuthToken ($user, $isRemember = null) {
        $token_exp = $isRemember ? null : now()->addHour(4);
        return $user->createToken('auth_token',['*'],$token_exp);
    }
}
