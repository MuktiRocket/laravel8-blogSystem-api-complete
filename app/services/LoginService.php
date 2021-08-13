<?php

namespace App\services;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public function LoginServices($request)
    {
        $x = $request->user();
        dd($x);
        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            $accesstoken = $request->user()->createToken($request->user()->email)->accessToken;
            $data = [
                'user' => $request->user(),
                'access token' => $accesstoken
            ];
        }
        dd($data);
        return $data;
    }
}
