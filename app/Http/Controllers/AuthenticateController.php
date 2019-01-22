<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use JWTAuth;
use App\User;
use Hash;

class AuthenticateController extends Controller
{
    //
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login (Request $request)
    {
        $credentials = $request->only(['email','password']);

        if(!$token = JWTAuth::attempt($credentials))
        {
            return respone()->json(['error','Invalid credential'], 401);
        }

        return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
        $credentials = $request->only(['name','email','password']);
        $credentials = [
            'name'=>$credentials['name'],
            'email'=>$credentials['email'],
            'password'=>$credentials['password']
        ];

        try {
            $user =$this->user->create($credentials);
        } catch (Exception $e) {
            return response()->json(['error' => 'User already exist'], 409);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('token'));  
    }
}
