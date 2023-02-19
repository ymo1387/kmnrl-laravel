<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Specification;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AccountResource;

class AuthController extends Controller
{
    // product specifications create
    // public function specificationsForm (Request $req) {
    //     foreach ($req->spec as $k => $v) {
    //         if (!$v) {
    //             continue;
    //         }
    //         $data = [
    //             'product_id' => $req->product_id,
    //             'index' => $k,
    //             'text' => $v,
    //         ];
    //         Specification::create($data);
    //     }
    //     return to_route('form');
    // }

    public function account () {
        $user = auth()->user()->load('info');
        return new AccountResource($user);
    }

    public function login (LoginRequest $req) {
        $credentials = $req->validated();
        if (!Auth::attempt($credentials)) {
            return response()->json(["errors"=>['password'=>'Password incorrect']], 401);
        }

        $user = Auth::user();
        $user->tokens()->delete();
        $isRemember = filter_var($req['remember'], FILTER_VALIDATE_BOOLEAN);

        $token = $this->createAuthToken($user, $isRemember);
        return response()->json(['user'=>$user,'token'=>$token->plainTextToken], 200);
        // return response()->json(['user'=>$user,'token'=>$token->plainTextToken,'expires'=>$token->accessToken->expires_at], 200);
    }

    public function register (RegisterRequest $req) {
        $data = $req->validated();

        $user = User::create([
            'username' => $data['username'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
        $isRemember = filter_var($req['remember'], FILTER_VALIDATE_BOOLEAN);

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
